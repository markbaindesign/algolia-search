<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

if (!function_exists('algolia_add_to_record_post_title')):
   /**
    * Add Post Type to Record
    * @since               1.6.0
    * @param   array       $record
    * @param   WP_Post     $post
    */
   function algolia_add_to_record_post_title($record, WP_Post $post)
   {
      $record['title'] = apply_filters(
         'algolia_filter_add_to_record_post_title',
         $post->post_title,
         $post
      );
      return $record;
   }
endif;
add_filter('add_to_record_post_title', 'algolia_add_to_record_post_title', 10, 2);
