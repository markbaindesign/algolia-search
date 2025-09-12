<?php
// Scripts

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

require_once BD616__PLUGIN_DIR . 'inc/scripts/enqueue_default_scripts.php';
require_once BD616__PLUGIN_DIR . 'inc/scripts/get_algolia_translations.php';
require_once BD616__PLUGIN_DIR . 'inc/scripts/get_script_handles.php';
require_once BD616__PLUGIN_DIR . 'inc/scripts/register_algolia_script_global.php';
require_once BD616__PLUGIN_DIR . 'inc/scripts/register_algolia_script_global_advanced.php';
require_once BD616__PLUGIN_DIR . 'inc/scripts/register_algolia_scripts.php';
require_once BD616__PLUGIN_DIR . 'inc/scripts/register_scripts_modal.php';
require_once BD616__PLUGIN_DIR . 'inc/scripts/scripts.php';
