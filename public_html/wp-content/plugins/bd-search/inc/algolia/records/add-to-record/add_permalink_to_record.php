<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

/**
 * Add Permalink
 */
function algolia_add_permalink_to_record($record, $post_id)
{
   $url = get_permalink($post_id);
   if ($url) {
      $record['url'] = $url;
   }
   return $record;
}
add_filter('add_permalink_to_record', 'algolia_add_permalink_to_record', 10, 2);