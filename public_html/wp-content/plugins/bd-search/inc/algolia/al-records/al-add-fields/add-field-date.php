<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

if (!function_exists('algolia_add_to_record_post_date')):
   /**
    * Add Post Type to Record
    * @since               1.6.0
    * @param   array       $record
    * @param   WP_Post     $post
    */
   function algolia_add_to_record_post_date($record, WP_Post $post)
   {
      $date_format = apply_filters(
         'bd_filter_algolia_date_format', 
         'F j, Y', 
         $post
      );

      $record['date'] = apply_filters(
         'algolia_filter_add_to_record_post_date',
         get_the_date($date_format, $post->ID),
         $post
      );

      return $record;
   }
endif;
add_filter('add_to_record_post_date', 'algolia_add_to_record_post_date', 10, 2);
