<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

/**
 * Add Search Priority data to record
 * ==================================
 * 
 * Add an integer to the search record to allow "sort-by" to promote search 
 * result with higher priorities.
 * 
 * Useful mostly for when there is no search query or filters (e.g.
 * on page load).
 * 
 */
function algolia_add_search_priority_to_record($record, $post_id)
{
   $search_priority = get_post_meta($post_id, 'search_priority', true);

   // Convert to Int
   $search_priority = intval($search_priority);

   // Check it's a number
   if (is_numeric($search_priority)) {
      $record['search_priority'] = $search_priority;
   }

   return $record;
}
add_filter('add_search_priority_to_record', 'algolia_add_search_priority_to_record', 10, 2);