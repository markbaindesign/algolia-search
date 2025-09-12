<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

if (!function_exists('algolia_add_to_record_post_type')):
   /**
    * Add Post Type to Record
    * @since               1.6.0
    * @param   array       $record
    * @param   WP_Post     $post
    */
   function algolia_add_to_record_post_type($record, WP_Post $post)
   {

      $wordpress_post_type = apply_filters(
         'bd324_filter_wordpress_post_type',
         get_post_type($post->ID)
      );

      // Get Singular Name
      $post_type_obj = get_post_type_object($wordpress_post_type);
      $wordpress_post_type_name = $post_type_obj->labels->singular_name;

      if ($wordpress_post_type) {
         $record['wordpress_post_type']['type'] = $wordpress_post_type;
         $record['wordpress_post_type']['name'] = $wordpress_post_type_name;
      }
      return $record;
   }
endif;
add_filter('add_to_record_post_type', 'algolia_add_to_record_post_type', 10, 2);
