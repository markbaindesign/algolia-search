<?php
// Algolia

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

/* Autoloader */
$autoload = BD616__PLUGIN_DIR . '/vendor/autoload.php';
require_once $autoload;

global $algolia;

if(defined('ALGOLIA_APPLICATION_ID') && defined('ALGOLIA_APPLICATION_ID')){
   $algolia = \Algolia\AlgoliaSearch\SearchClient::create(ALGOLIA_APPLICATION_ID, ALGOLIA_API_KEY);
}

require_once BD616__PLUGIN_DIR . '/inc/algolia/al-filters/al-filters.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/al-helpers/al-helpers.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/al-records/al-records.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/al-settings/al-settings.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/updates/updates.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/al-wp-cli/al-wp-cli.php';