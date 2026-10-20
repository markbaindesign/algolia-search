<?php // 

if (!defined('ABSPATH')) {
   die('Invalid request, dude!');
}

/**
 * Parse post IDs
 *
 * Accepts a comma-separated string ('1, 2,3') or an array of IDs.
 *
 * @param string|array $post_ids
 * @return int[] Unique, positive post IDs
 */
function bd324_parse_post_ids($post_ids)
{
   if (is_string($post_ids)) {
      $post_ids = explode(',', $post_ids);
   }
   if (!is_array($post_ids)) {
      return [];
   }

   $post_ids = array_map('trim', array_map('strval', $post_ids));
   $post_ids = array_filter($post_ids, 'ctype_digit');
   $post_ids = array_filter(array_map('intval', $post_ids));

   return array_values(array_unique($post_ids));
}

/**
 * Write a WP-CLI message (no-op outside WP-CLI, e.g. the admin screen)
 */
function bd324_algolia_cli_message($type, $message)
{
   if (defined('WP_CLI') && WP_CLI) {
      WP_CLI::$type($message);
   }
}

/**
 * Update the records for specific posts
 *
 * Each post is saved to (or removed from) every index it belongs in, in its
 * own language. Nothing is cleared or re-indexed.
 *
 * @param int[]       $post_ids
 * @param string|null $lang     If set, only posts in this WPML language are updated
 * @return int Number of records saved
 */
function bd324_algolia_update_posts($post_ids, $lang = null)
{
   $count = 0;

   foreach ($post_ids as $post_id) {
      $post = get_post($post_id);

      if (!($post instanceof WP_Post)) {
         bd324_algolia_cli_message('warning', "Post #{$post_id} not found, skipped.");
         continue;
      }

      if ($lang) {
         $post_lang = bd324_get_post_language_code($post_id);
         if ($post_lang !== '' && $post_lang !== $lang) {
            bd324_algolia_cli_message('warning', "Post #{$post_id} is in '{$post_lang}', not '{$lang}', skipped.");
            continue;
         }
      }

      if (bd324_update_algolia_record($post_id, $post)) {
         $count++;
      } else {
         bd324_algolia_cli_message('log', "Post #{$post_id} is not published, removed from its indices.");
      }
   }

   bd324_algolia_cli_message($count > 0 ? 'success' : 'warning', "{$count} record(s) updated.");

   return $count;
}

function bd324_algolia_update_command($index = 'global', $post_ids = [], $verbose = null, $lang = null)
{
   /**
    * Reindex Posts
    *
    * Update the indices used for the main global search function.
    * Each language has its own index.
    * Pass the $lang and $index params e.g. --lang=chs
    *
    * With $post_ids (e.g. --post_ids=1,2,3), only those records are updated
    * and the index is not cleared.
    *
    */

   // Vars
   $algolia_index_name = $index;
   $algolia_index_language = $lang;

   /**
    * POSTS
    * 
    * Update Algolia index for specific post IDs.
    * Returns before the full re-index below, which clears the index first.
    */
   // Any given value counts, so `--post_ids=` or a bare `--post_ids` errors
   // instead of falling through to a full re-index.
   if ($post_ids !== [] && $post_ids !== null) {
      $algolia_post_ids = bd324_parse_post_ids($post_ids);

      if (empty($algolia_post_ids)) {
         bd324_algolia_cli_message('error', 'No valid post IDs given, e.g. --post_ids=1,2,3');
         return 0;
      }

      return bd324_algolia_update_posts($algolia_post_ids, $algolia_index_language);
   }

   /**
    * INDEX
    *
    * Update the Algolia index for all post types.
    * Pass the $lang and $index params e.g. --lang=chs
    */
   if (!empty($algolia_index_name)) {
      /* Get post types */
      $post_types = bd324_get_post_types_for_index($algolia_index_name);

      $update_index = bd324_algolia_update_index(
         $algolia_index_name,
         $algolia_index_language,
         $post_types,
         $post_ids,
      );
      /**
       * Display the number of records indexed
       */
      // $algolia_full_index_name = WP_CLI::colorize("%Y" . $update_index['algolia_full_index_name'] . "%n");

      // $count_display = WP_CLI::colorize("%B" . $update_index['count'] . "%n");
      if ($update_index['count'] > 0) {
         // WP_CLI::success("[$algolia_full_index_name] $count_display entries reindexed");
         error_log(print_r([
            'algolia_full_index_name' => $update_index['algolia_full_index_name'],
            'count_display' => $update_index['count']
         ], true));
      } else {
         // WP_CLI::warning("[$algolia_full_index_name] $count_display entries reindexed ");
      }
   }
}
