<?php

if (!(defined('WP_CLI') && WP_CLI)) {
   return;
}

require_once BD616__PLUGIN_DIR . '/inc/algolia/al-wp-cli/Algolia_Command.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/al-wp-cli/add_language_suffix_to_index_name.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/al-wp-cli/add_tax_term_archive_pages.php';
