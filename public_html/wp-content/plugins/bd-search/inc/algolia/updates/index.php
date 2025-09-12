<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude!');
}

require_once BD616__PLUGIN_DIR . '/inc/algolia/updates/helpers/index.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/updates/update_algolia_record.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/updates/update_global_index.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/updates/update_algolia_index.php';