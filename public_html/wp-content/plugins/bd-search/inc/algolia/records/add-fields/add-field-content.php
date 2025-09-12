<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

/**
 * Add Content field to Record
 * @since               1.6.0
 * @param   array       $record
 * @param   WP_Post     $post
 */
if (!function_exists('bd324_add_field_to_record_content')):
   function bd324_add_field_to_record_content($record, WP_Post $post)
   {
      $content = get_the_content('', '', $post);
      $record['content'] = apply_filters(
         'bd_filter_add_field_content',
         $content,
         $post
      );
      return $record;
   }
endif;
add_filter('add_to_record_post_content', 'bd324_add_field_to_record_content', 10, 2);
