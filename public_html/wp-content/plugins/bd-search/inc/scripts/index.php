<?php
// Scripts

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

require_once 'inc/enqueue_default_scripts.php';
require_once 'inc/get_algolia_translations.php';
require_once 'inc/get_script_handles.php';
require_once 'inc/register_algolia_script_global.php';
require_once 'inc/register_algolia_script_global_advanced.php';
require_once 'inc/register_algolia_scripts.php';
require_once 'inc/register_scripts_modal.php';
