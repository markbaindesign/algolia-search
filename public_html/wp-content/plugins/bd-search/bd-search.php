<?php

/*
   Plugin Name: BD Algolia Search
   Description: Search powered by Algolia - core plugin functionality.
   Author: Bain Design
   Version: 2.5.0
   Author URI: http://bain.design
   License: GNU General Public License v2.0
   License URI: http://www.gnu.org/licenses/gpl-2.0.html
   Text Domain: _bd_algolia_search_plugin
   Domain Path: /languages/
 */

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

if(
   !defined('ALGOLIA_APPLICATION_ID') ||
   !defined('ALGOLIA_SEARCH_API_KEY') ||
   !defined('ALGOLIA_API_KEY')
){
   error_log(print_r("Missing Algolia Keys!", true));
   return;
}

define('BD616__PLUGIN_FILE', __FILE__);
define('BD616__PLUGIN_HANDLE', 'bd-algolia-search');
define('BD616__PLUGIN_NAME', __('BD Algolia Search', '_bd_algolia_search_plugin'));
define('BD616__PLUGIN_VERSION', '2.5.0');
define('BD616__PLUGIN_DIR', untrailingslashit(dirname(BD616__PLUGIN_FILE)));
define('BD616__PLUGIN_DIR_NAME', untrailingslashit(dirname(plugin_basename(BD616__PLUGIN_FILE))));


$plugin_dir_url = plugin_dir_url(__FILE__);
define('BD616__PLUGIN_URL', $plugin_dir_url);
define('BD616__SCRIPTS_URL', BD616__PLUGIN_URL . 'assets/js');
define('BD616__STYLES_URL', BD616__PLUGIN_URL . 'assets/css');
define('BD616__IMAGES_URL', BD616__PLUGIN_URL . 'assets/images');

/* Includes */
require_once BD616__PLUGIN_DIR . '/inc/inc.php';

// Load plugin textdomain
function BD616_load_textdomain()
{
   // https://developer.wordpress.org/reference/functions/load_plugin_textdomain/
   load_plugin_textdomain(
      '_bd_algolia_search_plugin',
      false,
      BD616__PLUGIN_DIR_NAME . '/languages'
   );
}
add_action('init', 'BD616_load_textdomain');
