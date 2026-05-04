<?php // Filters

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

// CSS selector that opens the search modal when clicked.
// Update to match the trigger element registered in the theme.
add_filter('BD616__filter_search_trigger_selector', function ($selector) {
    return '.__SLUG__-search-trigger';
});
