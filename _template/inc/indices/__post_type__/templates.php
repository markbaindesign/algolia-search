<?php // Index templates: __post_type__

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

// Add UI filter panels for the __POST_TYPE_LABEL__ index.
// Uncomment and extend once facet attributes are defined in settings.php.
//
// add_filter(
//     'BD616__filter_algolia_template_part_algolia-filters--__post_type__--advanced',
//     function ($output, $index) {
//         $output .= '<div id="algolia-filters--__post_type__--my-facet" class="algolia-facet"></div>';
//         return $output;
//     },
//     10,
//     2
// );
