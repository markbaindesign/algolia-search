<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

/**
 * Add Content Length field to Record
 * @since               1.6.0
 * @param   array       $record
 * @param   WP_Post     $post
 */
if (!function_exists('bd324_add_field_to_record_content_length')):
   function bd324_add_field_to_record_content_length($record, WP_Post $post)
   {
      $content = get_the_content('', '', $post);
      /**
       * Check length of content
       */
      $post_content_length = strlen($content);
      error_log(print_r($post_content_length, true));

      $record_content_length = strlen($record['content']);
      error_log(print_r($record_content_length, true));

      $record['content_length']['post'] = $post_content_length;
      $record['content_length']['record'] = $record_content_length;

      return $record;
   }
endif;
add_filter('add_to_record_post_content', 'bd324_add_field_to_record_content_length', 90, 2);
