<?php // Index config: __post_type__ > post types

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

// This index is dedicated to __POST_TYPE_LABEL__ records only.
add_filter('bd324_filter_get_post_types_for_index___post_type__', function ($post_types) {
    return ['__post_type__'];
});
