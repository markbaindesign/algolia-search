<?php // Index templates: __post_type__

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

// Add UI filter panels for the __POST_TYPE_LABEL__ index.
// Keys must match the attribute names registered in settings.php.
// Each key becomes the container ID: algolia-filters--__post_type__--{key}
// Wire up a refinementList widget for each in the advanced search JS.
//
// add_filter(
//     'BD616__filter_algolia_template_part_algolia-filters--__post_type__--advanced',
//     function ($output, $index) {
//         $facets = [
//             'my_taxonomy'  => 'My Taxonomy',
//             'my_year'      => 'Year',
//         ];
//         foreach ($facets as $key => $label) {
//             $output .= '<div class="algolia-facet-group">';
//             $output .= '<h3 class="algolia-facet-group__title">' . esc_html($label) . '</h3>';
//             $output .= '<div id="algolia-filters--__post_type__--' . $key . '" class="algolia-facet algolia-facet--list"></div>';
//             $output .= '</div>';
//         }
//         return $output;
//     },
//     10,
//     2
// );
