<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

/**
 * Add organisation data to record
 */
function algolia_add_org_to_record($record, $post_id)
{
   // Add the org to the data 
   // if different from title

   $org = get_field('institution', $post_id);
   $title = get_the_title($post_id);
   if ($org && $org !== $title) {
      $record['org'] = $org;
   }
   return $record;
}
add_filter('add_org_to_record', 'algolia_add_org_to_record', 10, 2);