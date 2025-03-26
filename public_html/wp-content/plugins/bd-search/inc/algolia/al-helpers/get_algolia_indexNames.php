<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

/**
 * Get Algolia Index names
 * 
 * @return array $index_names
 * 
 */
function get_algolia_indexNames($post_id)
{
   // Vars
   $post_type = get_post_type($post_id);
   $index_names = array();

   // Get language of post
   $post_language_details = apply_filters(
      'wpml_post_language_details',
      NULL,
      $post_id
   );

   if (!is_wp_error($post_language_details)) {
      $post_language_code = $post_language_details['language_code'];
   }

   // Note: 
   // Posts can appear in multiple indexes
   // Team posts appear in team, global, map

   if ($post_type === 'team') {
      if ($post_language_code === 'chs') {
         $index_names[] = 'team_chs';
      } elseif ($post_language_code === 'cht') {
         $index_names[] = 'team_cht';
      } else {
         $index_names[] = 'team';
      }
   }

   if (($post_type === 'team' || $post_type === 'institution' || $post_type === 'recipient') && ($post_language_code !== 'chs' && $post_language_code !== 'cht')) {
      $index_names[] = 'map';
   }

   /**
    * Global Index
    * ============
    *  Note: Recipients do not appear in the global index
    *
    */
   if ($post_type !== 'recipient') {
      if ($post_language_code === 'chs') {
         $index_names[] = 'global_chs';
      } elseif ($post_language_code === 'cht') {
         $index_names[] = 'global_cht';
      } else {
         $index_names[] = 'global';
      }
   }

   return $index_names;
}