<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

/**
 * Add main content to Algolia record
 */
function algolia_add_content_to_record($record, WP_Post $post)
{
   $content = get_the_content('', '', $post);
   // Removes Divi shortcodes
   $content = preg_replace('/\[\/?et_pb.*?\]/', '', $content);
   // error_log(print_r($content, true));
   $record['content'] = wp_strip_all_tags(strip_shortcodes($content));
   return $record;
}
add_filter('add_content_to_record', 'algolia_add_content_to_record', 10, 2);