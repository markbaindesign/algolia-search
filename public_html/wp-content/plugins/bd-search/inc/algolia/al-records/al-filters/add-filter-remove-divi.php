<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

/**
 * Remove Divi shortcodes from content
 * @since 1.6.0
 * @param string $content
 */
if (!function_exists('bd324_filter_remove_divi_shortcodes_from_content')):
   function bd324_filter_remove_divi_shortcodes_from_content($content, WP_Post $post)
   {
      // Removes Divi shortcodes
      $content = preg_replace('/\[\/?et_pb.*?\]/', '', $content);
      return $content;
   }
endif;
add_filter('bd_filter_add_field_content', 'bd324_filter_remove_divi_shortcodes_from_content', 10, 2);
