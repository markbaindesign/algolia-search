<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

/**
 * Truncate content to avoid Algolia limits
 * @since 1.6.0
 * @param string $content
 * @param WP_Post $post
 */
if (!function_exists('bd324_filter_truncate_content')):
   function bd324_filter_truncate_content($content, WP_Post $post)
   {
      /**
       * Check length of content and apply an arbitrary limit
       */
      $content_length = strlen($content);
      $max_length = apply_filters('bd_filter_max_content_length', 8000);
      if ($content_length > $max_length) {
         $content = substr($content, 0, $max_length);
         error_log(print_r("Content truncated!", true));
         error_log(
            sprintf(
               "Content String #%d Exceeds Maximum Length (size=%d/%d)",
               $post->ID,
               $content_length,
               $max_length
            )
         );
      }
      return $content;
   }
endif;
add_filter('bd_filter_add_field_content', 'bd324_filter_truncate_content', 90, 2); // Run after all the others
