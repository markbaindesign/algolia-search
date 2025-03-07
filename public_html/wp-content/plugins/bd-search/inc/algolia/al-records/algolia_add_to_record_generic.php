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
   $record = [
      'postID'       => $post_id,
      'objectID'     => implode('#', [$post_type, $post_id]),
      'title'        => $post->post_title,
      'excerpt'      => $post->post_excerpt,
      'content'      => strip_tags($post->post_content),
      'image'        => get_the_post_thumbnail_url($post_id),
   ];
   return $record;
}
add_filter('add_to_record_generic', 'algolia_add_to_record_generic', 10, 2);