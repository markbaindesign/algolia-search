<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

if (!function_exists('algolia_add_to_record_post_image')):
   /**
    * Add Image to Record
    * @since               1.6.0
    * @param   array       $record
    * @param   WP_Post     $post
    */
   function algolia_add_to_record_post_image($record, WP_Post $post)
   {
      $image_url = get_the_post_thumbnail_url($post->ID);
      if (!$image_url) {
         // Return the fallback image url
         $image_url = apply_filters(
            'bd324_filter_thumbnail_fallback_url',
            BD616__IMAGES_URL . '/fallback.jpg',
            $post
         );
      }
      $record['image'] = $image_url;
      return $record;
   }
endif;
add_filter('add_to_record_post_image', 'algolia_add_to_record_post_image', 10, 2);
