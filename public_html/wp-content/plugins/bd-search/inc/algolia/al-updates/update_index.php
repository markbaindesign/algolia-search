<?php // 

if (!defined('ABSPATH')) {
   die('Invalid request, dude!');
}

/**
 * Update Algolia index
 *
 * functionDescription
 * @param $functionParam       functionDescription
 */
if (!function_exists('bd324_algolia_update_index')):
   function bd324_algolia_update_index(
      $algolia_index_name,
      $algolia_index_language,
      $post_types,
      $post_ids,
   ) {
      global $algolia;
      $update_index = []; // To record the number of records indexed
      $update_index['count'] = 0;

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

      if (apply_filters('wpml_default_language', NULL) !== NULL) :
         // Switch language
         do_action('wpml_switch_language', $algolia_index_language);
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

            $record = [];

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
      $update_index['count'] = $count;
      $update_index['algolia_full_index_name'] = $algolia_full_index_name;

      return $update_index;
   }
endif;
