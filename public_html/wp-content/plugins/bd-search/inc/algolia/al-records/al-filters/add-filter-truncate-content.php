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
      // Convert $content to string if it's an array
      if (is_string($content)) {
         $content_length = strlen($content);
         $max_record = defined('ALGOLIA_MAX_RECORD_LENGTH') ? ALGOLIA_MAX_RECORD_LENGTH : 10000;
         $max_length = 0.8 * $max_record; // 80% of the max record size
         if ($content_length > $max_length) {
            $content = substr($content, 0, $max_length);
            error_log(
               sprintf(
                  "Record #%d Exceeds Maximum Content Length (size=%d/%d)... Truncated!",
                  $post->ID,
                  $content_length,
                  $max_length
               )
            );
         }
      }

      return $content;
   }
endif;
// add_filter('bd_filter_add_field_content', 'bd324_filter_truncate_content', 90, 2); // Run after all the others
