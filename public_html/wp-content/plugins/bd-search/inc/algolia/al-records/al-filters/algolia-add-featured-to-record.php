<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

/**
 * Add Featured to record
 */
function algolia_add_featured_to_record($record, $post_id)
{
   $featured = get_post_meta($post_id, 'is_featured', true);

   if ($featured) {
      $record['featured'] = $featured;
   }

   return $record;
}
add_filter('add_featured_to_record', 'algolia_add_featured_to_record', 10, 2);