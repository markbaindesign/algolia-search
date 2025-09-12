<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

/**
 * @since 1.6.0
 * 
 * Add filters here to add fields to the records.
 * Don't use these to manipulate the data you are adding, that's what 
 * `add-filters.php` is for.
 */

require_once BD616__PLUGIN_DIR . '/inc/algolia/al-records/al-add-fields/add-field-content-length.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/al-records/al-add-fields/add-field-content.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/al-records/al-add-fields/add-field-date.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/al-records/al-add-fields/add-field-excerpt.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/al-records/al-add-fields/add-field-image.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/al-records/al-add-fields/add-field-link.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/al-records/al-add-fields/add-field-location.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/al-records/al-add-fields/algolia_add_location_to_record.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/al-records/al-add-fields/add-field-object-id.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/al-records/al-add-fields/add-field-post-id.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/al-records/al-add-fields/add-field-post-type.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/al-records/al-add-fields/add-field-title.php';
