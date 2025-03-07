<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

if (!function_exists('algolia_add_to_record_post_excerpt')):
   /**
    * Add Excerpt to Record
    * @since               1.6.0
    * @param   array       $record
    * @param   WP_Post     $post
    */
   function algolia_add_to_record_post_excerpt($record, WP_Post $post)
   {
      $record['excerpt'] = apply_filters(
         'algolia_filter_add_to_record_post_excerpt',
         $post->post_excerpt,
         $post
      );

      return $record;
   }
endif;
add_filter('add_to_record_post_excerpt', 'algolia_add_to_record_post_excerpt', 10, 2);
