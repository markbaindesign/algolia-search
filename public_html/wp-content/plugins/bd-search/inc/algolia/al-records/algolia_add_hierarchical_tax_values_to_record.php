<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

/**
 * Add taxonomy data to record
 */
function algolia_add_hierarchical_tax_values_to_record($record, $post_id, $tax)
{
   // https://www.algolia.com/doc/api-reference/widgets/hierarchical-menu/js/

   // Get terms
   $terms = wp_get_post_terms($post_id, $tax);

   /**
    * For each term, loop through each level and add to string
    * Then, add string to $hierarchical_menu_data array
    */

   $hierarchical_menu_data = array();
   $lvl0 = array();
   $lvl1 = array();
   $lvl2 = array();

   foreach ($terms as $term) {
      $term_data = array();
      $term_id = $term->term_id;
      $term_name = $term->name;
      $term_parent = get_term($term->parent, 'grant_category');
      $term_parent_name = $term_parent->name;
      $term_grandparent = get_term($term_parent->parent, 'grant_category');
      $term_grandparent_name = $term_grandparent->name;

      if ($term_grandparent_name) {
         $lvl0[] = $term_grandparent_name;
         if ($term_parent_name) {
            $lvl1[] = $term_grandparent_name . ' > ' . $term_parent_name;
            if ($term_name) {
               $lvl2[] = $term_grandparent_name . ' > ' . $term_parent_name . ' > ' . $term_name;
            }
         }
      } elseif ($term_parent_name) {
         $lvl0[] = $term_parent_name;
         if ($term_name) {
            $lvl1[] = $term_parent_name . ' > ' . $term_name;
         }
      } elseif ($term_name) {
         $lvl0[] = $term_name;
      }
   }

   // Add terms to index
   if ($lvl0) {
      $term_data['lvl0'] = $lvl0;
   }
   $term_data['lvl1'] = $lvl1;
   $term_data['lvl2'] = $lvl2;

   $hierarchical_menu_data[] = $term_data;

   $record[$tax] = $hierarchical_menu_data;

   // Create the Grant Category slug

   // Get the first item in the array
   $grant_category_slug = '';
   if (isset($lvl2[0])) {
      $grant_category_slug = $lvl2[0];
   }

   if ($grant_category_slug) {
      // Reformat the string

      // Convert to lowercase
      $grant_category_slug = strtolower($grant_category_slug);

      // Remove spaces
      $grant_category_slug = preg_replace('![^-\pL\pN\s]+!u', '', $grant_category_slug);

      // Replace spaces with hyphens
      $grant_category_slug = preg_replace('![-\s]+!u', '-', $grant_category_slug);

      // Add the Grant Slug record
      $record["grant_category_slug"] = trim($grant_category_slug, '-');

      // Add the English Grant term ID
      // For styling
      $id_en = apply_filters('wpml_object_id', $term_id, 'grant_category', false, 'en');
      $record["grant_term_id"] = $id_en;
   }

   return $record;
}
add_filter('add_hierarchical_tax_values_to_record', 'algolia_add_hierarchical_tax_values_to_record', 10, 3);