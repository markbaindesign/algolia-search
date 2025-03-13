<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

require_once BD616__PLUGIN_DIR . '/vendor/autoload.php';

require_once 'admin/admin.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/algolia.php';
require_once BD616__PLUGIN_DIR . '/inc/form.php';
require_once BD616__PLUGIN_DIR . '/inc/modal.php';
require_once 'scripts/scripts.php';
require_once 'shortcodes/shortcodes.php';
require_once 'styles/styles.php';
require_once BD616__PLUGIN_DIR . '/inc/wpml.php';
require_once BD616__PLUGIN_DIR . '/inc/templates/templates.php';