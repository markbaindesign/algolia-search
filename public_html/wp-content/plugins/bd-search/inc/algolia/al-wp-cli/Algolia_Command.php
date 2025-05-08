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

      $algoliaIndex = $algolia->initIndex($algolia_full_index_name);
      $algoliaIndex->clearObjects()->wait();

      $paged = 1;
      $count = 0;

      /* Get post types */
      $post_types = bd324_get_post_types_for_index($algolia_index_name);

      if (isset($assoc_args['verbose'])) {
         WP_CLI::line('Indexing Post Types: [' . implode(", ", $post_types) . ']');
      }

      if (apply_filters('wpml_default_language', NULL) !== NULL) :
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

         if (!$posts->have_posts()) {
            break;
         }

         $records = [];

         /* Add posts to records */
         foreach ($posts->posts as $post) {

            // Check post is allowed in the index
            if (!BD616__is_post_allowed($post->ID, get_post_type($post->ID), $algolia_index_name)) {
               continue;
            }

            // Convert post data to Algolia record
            $record = bd324_convert_post_data($post);

            /* Check record size does not exceed Algolia Max Record Size */
            if (!BD616_check_record_size($record, $post->ID)) {
               continue;
            };

            /* Add record to array */
            $records[] = $record;
            $count++;
         }

         /* Add taxonomies to records */
         $records = apply_filters(
            'bd324_filter_add_to_records_tax_terms', 
            $records, 
            $algolia_index_name, 
            $algolia_index_language
         );

         /* Filter records */
         $records = apply_filters(
            'bd324_filter_records_before_indexing', 
            $records, 
            $algolia_index_name, 
            $algolia_index_language
         );
         
         $records = mb_convert_encoding($records, 'UTF-8', 'UTF-8');

         /* Save records to the index */
         $algoliaIndex->saveObjects($records);

         $paged++;
      } while (true);

      // Set settings
      algolia_index_config($algoliaIndex, $algolia_full_index_name);

      $algolia_full_index_name = WP_CLI::colorize("%Y" . $algolia_full_index_name . "%n");
      $count_display = WP_CLI::colorize("%B" . $count . "%n");
      if ($count > 0) {
         WP_CLI::success("[$algolia_full_index_name] $count_display entries reindexed");
      } else {
         WP_CLI::warning("[$algolia_full_index_name] $count_display entries reindexed ");
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
