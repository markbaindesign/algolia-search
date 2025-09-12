<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

if (!function_exists('algolia_add_to_record_post_link')):
   /**
    * Add Content Length field to Record
    * @since               1.6.0
    * @param   array       $record
    * @param   WP_Post     $post
    */
   function algolia_add_to_record_post_link($record, WP_Post $post)
   {
      $record['url'] = apply_filters(
         'algolia_filter_add_to_record_post_link',
         get_permalink($post->ID),
         $post
      );
      return $record;
   }
endif;
add_filter('add_to_record_post_link', 'algolia_add_to_record_post_link', 10, 2);
