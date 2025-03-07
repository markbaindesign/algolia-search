<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

// Defaults
// Add new records in custom plugin!
require_once BD616__PLUGIN_DIR . '/inc/algolia/al-records/add-page.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/al-records/add-records.php';
// require_once BD616__PLUGIN_DIR . '/inc/algolia/al-records/add-tax.php';
// require_once BD616__PLUGIN_DIR . '/inc/algolia/al-records/add-term.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/al-records/al-records.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/al-records/filters.php';
