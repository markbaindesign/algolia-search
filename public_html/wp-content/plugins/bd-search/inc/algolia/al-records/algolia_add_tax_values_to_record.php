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
   $term = reset($terms);
   isset($term->name) ? $term_data['display'] = $term->name : '';
   isset($term->slug) ? $term_data['name'] = $term->slug : '';

   $record[$tax] = $term_data;

   return $record;
}
add_filter('add_tax_values_to_record', 'algolia_add_tax_values_to_record', 10, 3);