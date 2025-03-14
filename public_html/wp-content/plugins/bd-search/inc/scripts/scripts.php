<?php
// Scripts

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

require_once 'functions/get_script_handles.php';
require_once 'functions/register_algolia_scripts.php';
require_once 'functions/load_default_scripts.php';
require_once 'functions/register_algolia_script_global.php';
require_once 'functions/register_algolia_script_global_shortcode.php';
