<?php // 

if (!defined('ABSPATH')) {
   die('Invalid request, dude!');
}

class Algolia_Command
{

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
      global $algolia;

      // Skip prompt
      if (isset($assoc_args['skip-prompt'])) {
         $skip_prompt = 'yes';
      } else {
         $skip_prompt = 'no';
      }
      // error_log(print_r($skip_prompt, true));

      // Display environment data
      WP_CLI::runcommand('algolia check_env');

      // Get user confirmation
      // WP_CLI::confirm("Are you sure you want to continue?", array('yes'));

      // Get index name
      if (isset($assoc_args['index'])) {
         $algolia_index_name = $assoc_args['index'];
      } else {
         $algolia_index_name = 'global';
      }

      // Get post types to index
      if (isset($assoc_args['post_types'])) { // To Do: check if this is working
         // Get post types string
         $algolia_index_post_types_args = $assoc_args['post_types'];
         // Convert to array
         $algolia_index_post_types = explode(',', $algolia_index_post_types_args);
      } else {
         $algolia_index_post_types = '';
      }

      // Get language
      if (isset($assoc_args['lang'])) {
         $algolia_index_language = $assoc_args['lang'];
      } else {
         $algolia_index_language = '';
      }

      // Get Post IDs to index
      if (isset($assoc_args['id'])) {
         $algolia_ids_string = $assoc_args['id'];
         $post_ids = explode(',', $algolia_ids_string);
      } else {
         $post_ids = [];
      }

      /**
       * Get full index name
       * Includes table prefix & language parameter
       */
      $algolia_full_index_name = apply_filters(
         'bd324_get_full_index_name',
         $algolia_index_name,
         $algolia_index_language,
      );

      $indexGlobal = $algolia->initIndex($algolia_full_index_name);
      $indexGlobal->clearObjects()->wait();

      $paged = 1;
      $count = 0;

      // Get post types
      if ($algolia_index_post_types) {
         $post_types = $algolia_index_post_types;
      } else {
         $post_types = bd324_get_post_types_for_index($algolia_index_name);
      }

      if (isset($assoc_args['verbose'])) {
         WP_CLI::line('Indexing Post Types: [' . implode(", ", $post_types) . ']');
      }

      if (function_exists('wpml_switch_language')):
         // Switch language
         do_action('wpml_switch_language', $algolia_index_language);
         if (isset($assoc_args['verbose'])) {
            WP_CLI::line('Switching language to [' . $algolia_index_language . ']');
         }
      endif;

      do {

         // Get query args
         if (function_exists('bd324_get_args_for_query')):
            $args = bd324_get_args_for_query(
               $algolia_index_name,
               $algolia_index_language,
               $post_types,
               $post_ids,
               $paged
            );
         endif;

         $posts = new WP_Query($args);

         $post_count = $posts->post_count;
         if (isset($assoc_args['verbose'])) {
            WP_CLI::line('Query has returned ' . $post_count . ' posts to index!');
         }

         if (!$posts->have_posts()) {
            break;
         }

         $records = [];

         foreach ($posts->posts as $post) {

            $post_id = $post->ID;
            $post_type = get_post_type($post_id);

            // Check post is allowed
            if (function_exists('BD616__is_post_allowed')):
               if (!BD616__is_post_allowed($post_id, $post_type, $algolia_index_name)) {
                  // continue;
               }
            endif;

            if (isset($assoc_args['verbose'])) {
               WP_CLI::line('Indexing [' . $post->post_type . '][' . $post->post_title . ']');
            }

            // Convert post data to Algolia record
            $record = bd324_convert_post_data($post);

            /* Check record size does not exceed Algolia Max Record Size */
            $sizeOk = BD616_check_record_size($record, $post_id);
            if ($sizeOk) {
               // Add record to array
               $records[] = $record;
               $count++;
            }
         }

         $records = apply_filters('bd324_filter_records_before_indexing', $records, $algolia_index_name, $algolia_index_language);
         $records = mb_convert_encoding($records, 'UTF-8', 'UTF-8');
         $indexGlobal->saveObjects($records);

         $paged++;
      } while (true);

      // Set settings
      algolia_index_config($indexGlobal, $algolia_full_index_name);

      $algolia_full_index_name = WP_CLI::colorize("%Y" . $algolia_full_index_name . "%n");
      $count = WP_CLI::colorize("%B" . $count . "%n");
      WP_CLI::success("$count entries reindexed [$algolia_full_index_name]");
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
