<?php // 

if(!defined('ABSPATH')) {
   die('Invalid request, dude!');
}

require_once 'functions/BD616__searchform_shortcode.php';
require_once 'functions/BD616__search.php';

// Allow shortcodes in widgets
add_filter('widget_text', 'do_shortcode');