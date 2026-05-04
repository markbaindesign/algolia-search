<?php

/*
 Plugin Name: __FULL_NAME__
 Description: Adds project-specific indices, post types, and filters to the core BD Algolia Search plugin.
 Author: Bain Design
 Version: __VERSION__
 Author URI: http://bain.design
 License: GNU General Public License v2.0
 License URI: http://www.gnu.org/licenses/gpl-2.0.html
 Text Domain: __TEXT_DOMAIN__
 Domain Path: /languages/
 */

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

if (
    !defined('ALGOLIA_APPLICATION_ID') ||
    !defined('ALGOLIA_SEARCH_API_KEY') ||
    !defined('ALGOLIA_API_KEY')
) {
    return;
}

define('__CONST__PLUGIN_FILE', __FILE__);
define('__CONST__PLUGIN_HANDLE', '__SLUG__');
define('__CONST__PLUGIN_NAME', __('__FULL_NAME__', '__TEXT_DOMAIN__'));
define('__CONST__PLUGIN_VERSION', '__VERSION__');
define('__CONST__PLUGIN_DIR', untrailingslashit(dirname(__CONST__PLUGIN_FILE)));
define('__CONST__PLUGIN_DIR_NAME', untrailingslashit(dirname(plugin_basename(__CONST__PLUGIN_FILE))));
$plugin_dir_url = plugin_dir_url(__FILE__);
define('__CONST__PLUGIN_URL', $plugin_dir_url);
define('__CONST__SCRIPTS_URL', __CONST__PLUGIN_URL . 'assets/js');
define('__CONST__STYLES_URL', __CONST__PLUGIN_URL . 'assets/css');
define('__CONST__IMAGES_URL', __CONST__PLUGIN_URL . 'assets/images');

require_once __CONST__PLUGIN_DIR . '/inc/index.php';

function __CONST__load_textdomain()
{
    load_plugin_textdomain(
        '__TEXT_DOMAIN__',
        false,
        __CONST__PLUGIN_DIR_NAME . '/languages'
    );
}
add_action('init', '__CONST__load_textdomain');
