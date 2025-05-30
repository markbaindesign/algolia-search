<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

/**
 * Add generic post data to Algolia record
 */
function algolia_add_to_record_generic($record, WP_Post $post)
{
   $post_id = $post->ID;
   $post_type = get_post_type($post_id);
   $record['postID'] = apply_filters('bd324_filter_add_to_record_postID', $post_id, $post);
   $record['objectID'] = apply_filters('bd324_filter_add_to_record_objectID', implode('#', [$post_type, $post_id]), $post);
   $record['title'] = apply_filters('bd324_filter_add_to_record_title', $post->post_title, $post);
   $record['excerpt'] = apply_filters('bd324_filter_add_to_record_excerpt', $post->post_excerpt, $post);
   $record['content'] = apply_filters('bd324_filter_add_to_record_content', strip_tags($post->post_content), $post);
   $record['image'] = apply_filters('bd324_filter_add_to_record_image', get_the_post_thumbnail_url($post_id), $post);
   return $record;
}
add_filter('add_to_record_generic', 'algolia_add_to_record_generic', 10, 2);