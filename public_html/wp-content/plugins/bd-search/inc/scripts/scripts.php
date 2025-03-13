<?php
// Scripts

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

/* Load default scripts */
require_once BD616__PLUGIN_DIR . '/inc/scripts/algolia-scripts.php';

/* Load custom scripts */
require_once BD616__PLUGIN_DIR . '/inc/scripts/algolia-scripts-custom.php';
