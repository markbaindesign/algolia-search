<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

if (!function_exists('algolia_add_to_record_object_id')):
   /**
    * Add Object ID to Record
    * @since               1.6.0
    * @param   array       $record
    * @param   WP_Post     $post
    */
   function algolia_add_to_record_object_id($record, WP_Post $post)
   {
      $record['objectID'] = apply_filters(
         'algolia_filter_add_to_record_object_id',
         implode('#', [$post->post_type, $post->ID]),
         $post
      );
      return $record;
   }
endif;
add_filter('add_to_record_object_id', 'algolia_add_to_record_object_id', 10, 2);
