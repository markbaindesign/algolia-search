<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

require_once BD616__PLUGIN_DIR . '/inc/algolia/al-helpers/algolia_post_index_name.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/al-helpers/bd324_algolia_get_full_index_name.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/al-helpers/bd324_get_algolia_index_name.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/al-helpers/bd324_get_post_types_for_index.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/al-helpers/bd324_get_taxonomies_for_index.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/al-helpers/get_algolia_indexName.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/al-helpers/get_algolia_index_post_types.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/al-helpers/get_algolia_indexNames.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/al-helpers/BD616_check_record_size.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/al-helpers/BD616__is_post_allowed.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/al-helpers/bd324_handle_big_data_in_value.php';