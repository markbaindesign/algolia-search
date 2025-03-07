<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

/**
 * Add language suffix to index name
 * 
 * @param array $index_names              Array of index names
 * @return array $index_names             Filtered array of index names
 */
add_filter(
   'BD8090__filter_get_algolia_indexNames',
   function ($index_names, $post_type, $post_id) {
      // Get language of post
      $post_language_details = apply_filters(
         'wpml_post_language_details',
         NULL,
         $post_id
      );
      if (!is_wp_error($post_language_details)) {
         $post_language_code = $post_language_details['language_code'];
         // If code found, add suffix
         // https://stackoverflow.com/a/7617643
         // Don't add suffix if EN
         if ($post_language_code !== 'en' && $post_language_code !== null && $post_language_code !== '') {
            foreach ($index_names as &$name) {
               $name = $name . '_' . $post_language_code;
            }
            unset($name);
         }
      }
      return $index_names;
   },
   100, // Later!
   3
);