<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

/**
 * Get Algolia Index name
 */
function get_algolia_indexName($post_id)
{
   global $algolia;

   // Vars
   $index_name = '';
   $post_type = get_post_type($post_id);

   // Get language data in array
   $post_language_details = apply_filters(
      'wpml_post_language_details',
      NULL,
      $post_id
   );

   // Get the language code from the array
   if (!is_wp_error($post_language_details)) {
      $post_language_code = $post_language_details['language_code'];
   }

   // Note: 
   // Posts can appear in multiple indexes

   if ($post_type === 'team') {
      if ($post_language_code === 'chs') {
         $index_name = 'team_chs';
      } elseif ($post_language_code === 'cht') {
         $index_name = 'team_cht';
      } else {
         $index_name = 'team';
      }
   } else {
      if ($post_language_code === 'chs') {
         $index_name = 'global_chs';
      } elseif ($post_language_code === 'cht') {
         $index_name = 'global_cht';
      } else {
         $index_name = 'global';
      }
   }
   return $index_name;
}