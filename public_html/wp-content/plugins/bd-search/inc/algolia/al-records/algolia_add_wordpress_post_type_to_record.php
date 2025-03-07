<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

/**
 * Add Post Type to record
 */
function algolia_mwe_add_wordpress_post_type_to_record($record, $post_id)
{
   $wordpress_post_type = apply_filters(
      'bd324_filter_wordpress_post_type',
      get_post_type($post_id)
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
add_filter('add_wordpress_post_type_to_record', 'algolia_mwe_add_wordpress_post_type_to_record', 10, 2);