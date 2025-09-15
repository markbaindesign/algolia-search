<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

require_once BD616__PLUGIN_DIR . '/inc/algolia/helpers/BD616__is_post_allowed.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/helpers/BD616_check_record_size.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/helpers/add_language_suffix_to_index_name.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/helpers/bd324_algolia_get_full_index_name.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/helpers/bd324_get_post_types_for_index.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/helpers/bd324_handle_big_data_in_value.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/helpers/get_algolia_indexName.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/helpers/get_algolia_indexNames.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/helpers/get_algolia_index_name.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/helpers/get_algolia_index_post_types.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/helpers/get_algolia_post_index_name.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/helpers/get_args_for_query.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/helpers/remove-divi.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/helpers/strip-tags.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/helpers/truncate-content.php';