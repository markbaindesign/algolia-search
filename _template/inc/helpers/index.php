<?php // Helpers

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

// Fallback thumbnail shown when a post has no featured image.
add_filter('bd324_filter_thumbnail_fallback_url', function ($image_url, WP_Post $post) {
    return __CONST__IMAGES_URL . '/fallback.jpg';
}, 10, 2);
