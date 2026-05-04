<?php // Record: post

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

add_filter('post_to_record', '__CONST__post_to_record', 10, 2);

function __CONST__post_to_record($record, WP_Post $post)
{
    $post_id = $post->ID;
    $record = apply_filters('add_permalink_to_record', $record, $post_id);
    $record = apply_filters('add_wordpress_post_type_to_record', $record, $post_id);
    $record = apply_filters('add_content_to_record', $record, $post);
    $record = apply_filters('add_acf_content_to_record', $record, $post);
    return $record;
}
