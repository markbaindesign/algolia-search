<?php // Filters: __post_type__

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

// Strip or transform content before it is indexed.
// Runs at priority 15 — after Divi shortcode removal (10) and before truncation (90).
// add_filter('bd_filter_add_field_content', '__CONST____post_type___filter_content', 15, 2);

// function __CONST____post_type___filter_content($content, WP_Post $post)
// {
//     if (get_post_type($post) !== '__post_type__') {
//         return $content;
//     }
//     return preg_replace('/your pattern here/', '', $content);
// }
