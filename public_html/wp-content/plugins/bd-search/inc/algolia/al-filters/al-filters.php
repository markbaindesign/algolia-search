<?php // Algolia Filters

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

require_once BD616__PLUGIN_DIR . '/inc/algolia/al-filters/bd324_add_table_prefix_to_index_name.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/al-filters/bd324_kf_filter_records_before_indexing.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/al-filters/bd324_algolia_add_to_records_tax_terms.php';
