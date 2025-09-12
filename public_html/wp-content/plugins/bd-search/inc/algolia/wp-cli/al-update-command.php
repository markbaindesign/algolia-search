<?php // 

if (!defined('ABSPATH')) {
   die('Invalid request, dude!');
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
    */

   // Vars
   $algolia_index_name = $index;
   $algolia_index_language = $lang;
   $algolia_post_ids = [];

   // Get Post IDs to index
   if (!empty($post_ids) && is_string($post_ids)) :
      $post_ids = trim($post_ids);
      $algolia_post_ids = explode(',', $post_ids);
      $algolia_post_ids = array_map('trim', $algolia_post_ids);
      $algolia_post_ids = array_filter($algolia_post_ids, 'is_numeric');
      $algolia_post_ids = array_map('intval', $algolia_post_ids);
   endif;

   /**
    * POSTS
    * 
    * Update Algolia index for specific post IDs.
    */
   if (!empty($algolia_post_ids)) {
      // Loop through each post ID and update the Algolia record
      $update_index = []; // To record the number of records indexed
      $update_index['count'] = 0;
      foreach ($algolia_post_ids as $post_id) {
         $post = get_post($post_id);
         if ($post instanceof WP_Post) {
            $update = bd324_update_algolia_record($post_id, $post);
            if ($update) {
               $update_index['count']++;
            }
         }
      }
      // $count_display = WP_CLI::colorize("%B" . $update_index['count'] . "%n");
      if ($update_index['count'] === 0) {
         // WP_CLI::warning("$count_display entries reindexed ");
      } elseif ($update_index['count'] === 1) {
         // WP_CLI::success("$count_display entry reindexed ");
      } else {
         // WP_CLI::success("$count_display entries reindexed");
      }
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
