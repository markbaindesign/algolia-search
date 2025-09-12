<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

/**
 * Strip tags from content
 * @since 1.6.0
 * @param string $content
 */
if (!function_exists('bd324_filter_strip_tags_from_content')):
   function bd324_filter_strip_tags_from_content($content, WP_Post $post)
   {

      if (is_string($content)) {
         $content = wp_strip_all_tags(strip_shortcodes($content));
      }
      return $content;
   }
endif;
add_filter('bd_filter_add_field_content', 'bd324_filter_strip_tags_from_content', 20, 2);
