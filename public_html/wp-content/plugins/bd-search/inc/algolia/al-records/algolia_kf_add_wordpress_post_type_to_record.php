<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

/**
 * Add Post Type to record
 */
function algolia_kf_add_wordpress_post_type_to_record($record, $post_id)
{
   // Add the actual Post Type to the data
   // May not be the same as the Post type we display

   $wordpress_post_type = get_post_type($post_id);
   if ($wordpress_post_type) {
      $record['wordpress_post_type'] = $wordpress_post_type;
   }
   return $record;
}
add_filter('add_wordpress_post_type_to_record', 'algolia_kf_add_wordpress_post_type_to_record', 10, 2);