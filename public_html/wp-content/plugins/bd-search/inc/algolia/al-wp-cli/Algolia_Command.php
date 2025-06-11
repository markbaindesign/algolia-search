<?php // 

if (!defined('ABSPATH')) {
   die('Invalid request, dude!');
}

class Algolia_Command
{
   /**
    * Update the Algolia index
    */
   public function update($args, $assoc_args)
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
      $algolia_index_name = 'global';
      $algolia_index_language = '';
      $post_ids = [];

      if (!isset($assoc_args['silent'])) {
         // Display environment data
         WP_CLI::runcommand('algolia check_env');
      }

      // Get user confirmation
      if (!isset($assoc_args['silent'])) {
         WP_CLI::confirm("Are you sure you want to continue?", array('yes'));
      }

      // Get index name
      if (isset($assoc_args['index'])) {
         $algolia_index_name = $assoc_args['index'];
      }

      // Get language
      if (isset($assoc_args['lang'])) {
         $algolia_index_language = $assoc_args['lang'];
      }

      // Get Post IDs to index
      if (isset($assoc_args['id'])) {
         $algolia_ids_string = $assoc_args['id'];
         $post_ids = explode(',', $algolia_ids_string);
      }

      /**
       * POSTS
       * 
       * Update Algolia index for specific post IDs.
       */
      if (!empty($post_ids)) {
         if (isset($assoc_args['verbose'])) {
            WP_CLI::line('Indexing Post ID(s): [' . implode(", ", $post_ids) . ']');
         }
         // Loop through each post ID and update the Algolia record
         $update_index = []; // To record the number of records indexed
         $update_index['count'] = 0;
         foreach ($post_ids as $post_id) {
            $post = get_post($post_id);
            if ($post instanceof WP_Post) {
               $update = bd324_update_algolia_record($post_id, $post);
               if ($update) {
                  $update_index['count']++;
               }
            } else {
               WP_CLI::warning("Post with ID $post_id not found or is not a valid WP_Post object.");
            }
         }
         $count_display = WP_CLI::colorize("%B" . $update_index['count'] . "%n");
         if ($update_index['count'] === 0) {
            WP_CLI::warning("$count_display entries reindexed ");
         } elseif ($update_index['count'] === 1) {
            WP_CLI::success("$count_display entry reindexed ");
         } else {
            WP_CLI::success("$count_display entries reindexed");
         }
      }

      /**
       * INDEX
       *
       * Update the Algolia index for all post types.
       * Pass the $lang and $index params e.g. --lang=chs
       */
      if (!empty($assoc_args['index'])) {
         /* Get post types */
         $post_types = bd324_get_post_types_for_index($algolia_index_name);

         if (isset($assoc_args['verbose'])) {
            WP_CLI::line('Indexing Post Types: [' . implode(", ", $post_types) . ']');
         }

         $update_index = bd324_algolia_update_index(
            $algolia_index_name,
            $algolia_index_language,
            $post_types,
            $post_ids,
         );
         /**
          * Display the number of records indexed
          */
         $algolia_full_index_name = WP_CLI::colorize("%Y" . $update_index['algolia_full_index_name'] . "%n");

         $count_display = WP_CLI::colorize("%B" . $update_index['count'] . "%n");
         if ($update_index['count'] > 0) {
            WP_CLI::success("[$algolia_full_index_name] $count_display entries reindexed");
         } else {
            WP_CLI::warning("[$algolia_full_index_name] $count_display entries reindexed ");
         }
      }
   }

   /**
    * Provide information about the environment
    */
   public function check_env()
   {
      /**
       * Check the server, app ID, env
       */
      // DB Name
      if (defined('DB_NAME')) {
         WP_CLI::success("DB Name : " . DB_NAME);
      } else {
         WP_CLI::warning("DB Name not defined");
      }

      // WP ENV
      if (defined('WP_ENVIRONMENT_TYPE')) {
         WP_CLI::success("WP Env : " . WP_ENVIRONMENT_TYPE);
      } else {
         WP_CLI::warning('WP Env not defined');
      }

      // APP ID
      if (defined('ALGOLIA_APPLICATION_ID')) {
         WP_CLI::success("App ID : " . ALGOLIA_APPLICATION_ID);
      } else {
         WP_CLI::warning('App ID not defined');
      }
   }
}

WP_CLI::add_command('algolia', 'Algolia_Command');
