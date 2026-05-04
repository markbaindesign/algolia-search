<?php // Index config: global > post types

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

// Extend the default post types (post, page) with the custom post type.
// Add further post types here as the project grows.
add_filter('bd324_filter_get_post_types_for_index_global', function ($post_types) {
    $post_types[] = '__post_type__';
    return $post_types;
});
