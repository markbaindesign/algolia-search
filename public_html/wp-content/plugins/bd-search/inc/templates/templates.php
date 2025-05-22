<?php // Templates

if (!defined('ABSPATH')) {
   die('Invalid request, dude!');
}

$base_dir = plugin_dir_path(__FILE__);

require_once $base_dir . 'functions/show-search-template.php';
require_once $base_dir . 'functions/get-id.php';
require_once $base_dir . 'functions/get-classes.php';
require_once $base_dir . 'functions/get-part.php';
require_once $base_dir . 'functions/forms.php';
require_once $base_dir . 'functions/helpers.php';
require_once $base_dir . 'modal/index.php';
require_once $base_dir . 'advanced/index.php';
