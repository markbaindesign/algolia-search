<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

if (!function_exists('algolia_add_to_record_post_id')):
   /**
    * Add Post ID to Record
    * @since               1.6.0
    * @param   array       $record
    * @param   WP_Post     $post
    */
   function algolia_add_to_record_post_id($record, WP_Post $post)
   {
      $record['postID'] = apply_filters(
         'algolia_filter_add_to_record_post_id',
         $post->ID,
         $post
      );
      return $record;
   }
endif;
add_filter('add_to_record_post_id', 'algolia_add_to_record_post_id', 10, 2);
