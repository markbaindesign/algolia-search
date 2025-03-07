<?php // 

if(!defined('ABSPATH')) {
   die('Invalid request, dude!');
}

/**
 * Filter records before indexing
 * @since 1.0.0
 *
 * Add taxonomy term archive pages to records
 * @param array $records
 * @param string $algolia_index_name
 * @return array $records
 */
function bd324_kf_filter_records_before_indexing($records, $algolia_index_name, $algolia_index_language)
{
   // Add taxonomy terms to records
   
   // Get taxonomy terms
   $taxonomies = bd324_get_taxonomies_for_index($algolia_index_name);

   foreach ($taxonomies as $taxonomy) {
      $args = array(
         'taxonomy'     => $taxonomy,
         'hide_empty'   => false,
      );

      $terms = get_terms($args);

      if (!empty($terms) && !is_wp_error($terms)) {
         foreach ($terms as $term) {
            $record = (array) apply_filters(str_replace('-', '_', $term->taxonomy) . '_to_record', $term);

            if (!isset($record['objectID'])) {
               $record['objectID'] = implode('#', [$term->taxonomy, $term->term_id]);
            }

            $records[] = $record;
         }
      }
   }

   /**
    * Add taxonomy term archive pages to records
   */
   $term_records = (array) BD616_add_tax_term_archive_pages(
      $algolia_index_name,
      $algolia_index_language
   );
   $records = array_merge($records, $term_records); // Merge term records with existing records

   return $records;
}
add_filter('bd324_filter_records_before_indexing', 'bd324_kf_filter_records_before_indexing', 10, 3);
