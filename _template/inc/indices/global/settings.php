<?php // Index settings: global

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

// Note: the filter name includes the WordPress table prefix (default: wp_).
// Update if your install uses a different prefix.

add_filter(
    'bd324_filter_algolia_index_config_searchableAttributes_wp_global',
    '__CONST__global_searchable_attributes'
);
function __CONST__global_searchable_attributes()
{
    return [
        'title',
        'excerpt',
        'content',
        'acf_content',
    ];
}

add_filter(
    'bd324_filter_algolia_index_config_attributesForFaceting_wp_global',
    '__CONST__global_facet_attributes'
);
function __CONST__global_facet_attributes()
{
    return [
        'filterOnly(wordpress_post_type)',
    ];
}
