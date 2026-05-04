<?php // Filters: __post_type__

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

// Add custom facet processing, field transforms, or record modifications here.
// Example — strip a character from content before indexing:
// add_filter('bd_filter_add_field_content', function ($content, WP_Post $post) {
//     if (get_post_type($post) !== '__post_type__') {
//         return $content;
//     }
//     return str_replace('*', '', $content);
// }, 10, 2);
