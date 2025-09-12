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

require_once BD616__PLUGIN_DIR . '/inc/algolia/helpers/index.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/records/index.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/settings/index.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/updates/index.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/wp-cli/index.php';