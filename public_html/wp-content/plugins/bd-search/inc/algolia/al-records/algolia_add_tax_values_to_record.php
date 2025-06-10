<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

/**
 * Add taxonomy data to record
 */
function algolia_add_tax_values_to_record($record, $post_id, $tax)
{
   // Get first item in post terms
   $term_data = array();
   $terms = wp_get_post_terms($post_id, $tax);
   if (!$terms) {
      return $record;
   }
   foreach ($terms as $term) {
      $item = array();
      if (isset($term->name)) {
         $item['display'] = $term->name;
      }
      if (isset($term->slug)) {
         $item['name'] = $term->slug;
      }
      $term_data[] = $item;
   }

   $record[$tax . '-multiple'] = $term_data;

   return $record;
}
add_filter('add_tax_values_to_record', 'algolia_add_tax_values_to_record', 10, 3);

/**
 * Add taxonomy data to record
 */
function algolia_add_tax_values_to_record_single_value($record, $post_id, $tax)
{
   // Get first item in post terms
   $terms = wp_get_post_terms($post_id, $tax);
   if (empty($terms) || !isset($terms[0])) {
      return $record;
   }
   $term = $terms[0];
   $item = array();
   if (isset($term->name)) {
      $item['display'] = $term->name;
   }
   if (isset($term->slug)) {
      $item['name'] = $term->slug;
   }

   $record[$tax] = $item;

   return $record;
}
add_filter('add_tax_values_to_record', 'algolia_add_tax_values_to_record_single_value', 10, 3);
