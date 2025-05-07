<?php // 

if(!defined('ABSPATH')) {
   die('Invalid request, dude!');
}

/**
 * Add taxonomy terms to records array
 */
if (!function_exists('bd324_algolia_add_to_records_tax_terms')) :
   function bd324_algolia_add_to_records_tax_terms(
      array $records,
      $index_name,
      $algolia_index_language
   ) {
      if (!defined('WP_ENVIRONMENT_TYPE')) {
         /** @disregard */
         WP_CLI::line(WP_ENVIRONMENT_TYPE);
         WP_CLI::error('Environment not defined, exiting!');
      }

      if (isset($assoc_args['verbose'])) {
         WP_CLI::line('Adding taxonomies to records');
      }
      
      // Get taxonomy terms
      $taxonomies = apply_filters(
         'bd324_filter_taxonomies_to_index_' . $index_name,
         [], // Empty by default
         $index_name,
         $algolia_index_language
      );

      if (empty($taxonomies)) {
         if (isset($assoc_args['verbose'])) {
            WP_CLI::line('No taxonomies to add');
         }
         return $records;
      }

      foreach ($taxonomies as $taxonomy) {
         if (isset($assoc_args['verbose'])) {
            WP_CLI::line('Indexing Taxonomy: [' . $taxonomy . ']');
         }

         $args = array(
            'taxonomy'     => $taxonomy,
            'hide_empty'   => false,
         );

         $terms = get_terms($args);

         if (!empty($terms) && !is_wp_error($terms)) {
            foreach ($terms as $term) {
               if (isset($assoc_args['verbose'])) {
                  WP_CLI::line('Indexing [' . $term->taxonomy . '][' . $term->name . ']');
               }
               $record = (array) apply_filters(str_replace('-', '_', $term->taxonomy) . '_to_record', $term);

               /* Check record size does not exceed Algolia Max Record Size */
               $sizeOk = BD616_check_record_size($record, $term->term_id);
               if ($sizeOk === false) {
                  continue;
               }

               if (!isset($record['objectID'])) {
                  $record['objectID'] = implode('#', [$term->taxonomy, $term->term_id]);
               }

               $records[] = $record;
               // $count++;
            }
         }
      }

      return $records;
   }
endif;
add_filter('bd324_filter_add_to_records_tax_terms', 'bd324_algolia_add_to_records_tax_terms', 10, 3);
