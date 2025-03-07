<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

/**
 * Convert Post data to Algolia record
 */
function algolia_add_all_post_types_to_record(WP_Post $post)
{
   $record = [];

   $record = apply_filters('add_to_record_post_content',          $record,       $post);
   $record = apply_filters('add_to_record_post_date',             $record,       $post);
   $record = apply_filters('add_to_record_post_excerpt',          $record,       $post);
   $record = apply_filters('add_to_record_post_image',            $record,       $post);
   $record = apply_filters('add_to_record_post_link',             $record,       $post);
   $record = apply_filters('add_to_record_post_title',            $record,       $post);
   $record = apply_filters('add_to_record_post_type',             $record,       $post);
   $record = apply_filters('add_to_record_post_id',               $record,       $post);
   $record = apply_filters('add_to_record_object_id',             $record,       $post);

   return $record;
}
add_filter('all_post_types_to_record', 'algolia_add_all_post_types_to_record');
