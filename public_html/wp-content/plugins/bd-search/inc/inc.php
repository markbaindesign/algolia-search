<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

require_once BD616__PLUGIN_DIR . '/vendor/autoload.php';

require_once 'admin/admin.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/algolia.php';
require_once 'scripts/index.php';
require_once 'shortcodes/shortcodes.php';
require_once 'styles/index.php';
require_once BD616__PLUGIN_DIR . '/inc/wpml.php';
require_once 'templates/templates.php';