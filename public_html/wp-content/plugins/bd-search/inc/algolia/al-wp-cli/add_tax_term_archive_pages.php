<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

/**
 * Add taxonomy term archive pages to records
 * 
 * @param   string         $algolia_index_name
 * @param   string         $algolia_index_language
 * @return  array          $term_records
 */
function BD616_add_tax_term_archive_pages($algolia_index_name, $algolia_index_language)
{
   /**
    * Add Archive Pages as Pages to the Index.
    */

   $full_algolia_index_name = $algolia_index_name;
   if ($algolia_index_language) {
      $full_algolia_index_name .= '_' . $algolia_index_language;
   }

   // Get Taxonomies to add to Index
   $taxonomy_archive_pages_to_index = (array) apply_filters(
      'bd324_filter_taxonomy_archive_pages_to_index',
      array(),
      $full_algolia_index_name,
   );

   /**
    * Before running query, switch language
    */
   do_action('wpml_switch_language', $algolia_index_language);

   $term_records = [];

   foreach ($taxonomy_archive_pages_to_index as $taxonomy) {

      $taxonomy_obj = get_taxonomy($taxonomy);

      if (is_object($taxonomy_obj)) {

         // Run "To Record" filter for taxonomy
         // This will create the archive page with all the terms
         // $record = apply_filters(
         //    'taxonomy_archive_page_to_record',
         //    $taxonomy_obj
         // );

         // Add ObjectID
         // if (!isset($record['objectID'])) {
         //    $record['objectID'] = implode('#', ['archive', $taxonomy_obj->name]);
         // }

         /**
          * Terms
          */

         $args = array(
            'taxonomy' => $taxonomy,
         );
         $terms = get_terms($args);

         foreach ($terms as $term) {

            // Run "To Record" filter for term
            $record = (array) apply_filters(
               'term_archive_page_to_record',
               $term->term_id,
               $taxonomy_obj->name, // Name of Taxonomy
               $taxonomy_obj->label // Label of Taxonomy
            );

            // Add ObjectID
            if (!isset($record['objectID'])) {
               $record['objectID'] = implode('#', ['term', $term->slug]);
            }

            // Add Record to array
            $term_records[] = $record;
         }
      }
   }
   return $term_records;
}