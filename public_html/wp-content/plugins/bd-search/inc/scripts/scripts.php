<?php
// Scripts

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

require_once 'functions/get_script_handles.php';
require_once 'functions/load_scripts.php';
require_once 'functions/load_default_scripts.php';

/* Load custom scripts */
require_once BD616__PLUGIN_DIR . '/inc/scripts/algolia-scripts-custom.php';
