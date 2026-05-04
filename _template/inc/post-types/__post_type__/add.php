<?php // Record: __post_type__

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

add_filter('__post_type___to_record', '__CONST____post_type___to_record', 10, 2);

function __CONST____post_type___to_record($record, WP_Post $post)
{
    $post_id = $post->ID;
    $record = apply_filters('add_permalink_to_record', $record, $post_id);
    $record = apply_filters('add_wordpress_post_type_to_record', $record, $post_id);
    $record = apply_filters('add_content_to_record', $record, $post);
    $record = apply_filters('add_acf_content_to_record', $record, $post);
    $record = apply_filters('add_featured_to_record', $record, $post_id);
    $record = apply_filters('add_search_priority_to_record', $record, $post_id);
    // Add taxonomies:
    // $record = apply_filters('add_tax_values_to_record', $record, $post_id, 'my_taxonomy');
    // $record = apply_filters('add_hierarchical_tax_values_to_record', $record, $post_id, 'my_tax');
    return $record;
}
