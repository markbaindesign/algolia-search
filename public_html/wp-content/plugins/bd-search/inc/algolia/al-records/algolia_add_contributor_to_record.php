<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

/**
 * Add Contributor data to record
 */
function algolia_add_contributor_to_record($record, $post_id)
{
   // Clear any existing contributor data
   $record['contributor'] = array();
   $record['contributor']['id'] = get_the_author_meta('ID', get_post_field('post_author', $post_id));
   $record['contributor']['name']  = get_the_author_meta('display_name', get_post_field('post_author', $post_id));
   $record['contributor']['url'] = '';
   return $record;
}
add_filter('add_contributor_to_record', 'algolia_add_contributor_to_record', 10, 2);