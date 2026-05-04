<?php // Index templates: global

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

// Customise the search modal header.
add_filter(
    'BD616__filter_algolia_template_part_algolia-header--global--modal',
    function ($output, $index) {
        $output = '<h2 class="algolia-modal__title">Search __NAME__</h2>';
        return $output;
    },
    10,
    2
);
