<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

/**
 * @since 1.6.0
 * 
 * Add default filters here to manipulate the data you are adding to 
 * records via fields.
 * 
 * Don't use these to add fields to the records, that's what 
 * `add-fields.php` is for.
 * 
 * Remember that most of the manipulation should be done via filters in the 
 * client site custom plugin. 
 */


require_once BD616__PLUGIN_DIR . '/inc/algolia/al-records/al-filters/add-filter-remove-divi.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/al-records/al-filters/add-filter-strip-tags.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/al-records/al-filters/add-filter-truncate-content.php';
