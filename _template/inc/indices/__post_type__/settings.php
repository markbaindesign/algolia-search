<?php // Index settings: __post_type__

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

// Note: filter name includes the WordPress table prefix (default: wp_).

add_filter(
    'bd324_filter_algolia_index_config_searchableAttributes_wp___post_type__',
    '__CONST____post_type___searchable_attributes'
);
function __CONST____post_type___searchable_attributes()
{
    return [
        'title',
        'excerpt',
        'content',
        'acf_content',
        // Add field names that Algolia should search, e.g. 'my_taxonomy'
    ];
}

add_filter(
    'bd324_filter_algolia_index_config_attributesForFaceting_wp___post_type__',
    '__CONST____post_type___facet_attributes'
);
function __CONST____post_type___facet_attributes()
{
    return [
        // 'filterOnly(my_taxonomy)',
        // 'searchable(my_taxonomy)',
    ];
}

add_filter(
    'bd324_filter_algolia_index_config_ranking_wp___post_type__',
    '__CONST____post_type___ranking'
);
function __CONST____post_type___ranking()
{
    return [
        'desc(search_priority)',
        'typo', 'geo', 'words', 'filters', 'proximity', 'attribute', 'exact', 'custom',
    ];
}
