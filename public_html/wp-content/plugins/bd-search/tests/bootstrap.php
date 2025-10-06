<?php

/**
 * PHPUnit bootstrap file for BD Search Plugin
 *
 * @package BDSearch
 */

// Composer autoloader.
require_once dirname(__DIR__) . '/vendor/autoload.php';

// Initialize Brain/Monkey for WordPress function mocking
Brain\Monkey\setUp();

// Define plugin constants for testing
if (! defined('BD616__PLUGIN_DIR')) {
    define('BD616__PLUGIN_DIR', dirname(__DIR__));
}
if (! defined('BD616__PLUGIN_URL')) {
    define('BD616__PLUGIN_URL', 'http://algolia-search.local/wp-content/plugins/bd-search/');
}
if (! defined('BD616__SCRIPTS_URL')) {
    define('BD616__SCRIPTS_URL', BD616__PLUGIN_URL . 'assets/js');
}
if (! defined('BD616__PLUGIN_VERSION')) {
    define('BD616__PLUGIN_VERSION', '2.8.0');
}

// Mock Algolia constants for testing
if (! defined('ALGOLIA_APPLICATION_ID')) {
    define('ALGOLIA_APPLICATION_ID', 'test_app_id');
}
if (! defined('ALGOLIA_SEARCH_API_KEY')) {
    define('ALGOLIA_SEARCH_API_KEY', 'test_search_key');
}
if (! defined('ALGOLIA_API_KEY')) {
    define('ALGOLIA_API_KEY', 'test_api_key');
}

// Enable debug mode for testing
if (! defined('BD324_DEBUG')) {
    define('BD324_DEBUG', true);
}

// Mock ABSPATH
if (! defined('ABSPATH')) {
    define('ABSPATH', '/tmp/wordpress/');
}

// Mock essential WordPress functions before loading plugin files
if (! function_exists('add_shortcode')) {
    function add_shortcode($tag, $callback)
    {
        // Mock function for testing
        return true;
    }
}

if (! function_exists('add_action')) {
    function add_action($hook, $callback, $priority = 10, $accepted_args = 1)
    {
        // Mock function for testing
        return true;
    }
}

if (! function_exists('wp_enqueue_script')) {
    function wp_enqueue_script($handle, $src = '', $deps = array(), $ver = false, $in_footer = false)
    {
        // Mock function for testing
        return true;
    }
}

if (! function_exists('wp_localize_script')) {
    function wp_localize_script($handle, $object_name, $l10n)
    {
        // Mock function for testing
        return true;
    }
}

if (! function_exists('apply_filters')) {
    function apply_filters($hook_name, $value, ...$args)
    {
        // Mock function for testing
        return $value;
    }
}

if (! function_exists('esc_attr__')) {
    function esc_attr__($text, $domain = 'default')
    {
        // Mock function for testing
        return $text;
    }
}

if (! function_exists('__')) {
    function __($text, $domain = 'default')
    {
        // Mock function for testing
        return $text;
    }
}

if (! function_exists('plugin_dir_url')) {
    function plugin_dir_url($file)
    {
        // Mock function for testing
        return 'http://algolia-search.local/wp-content/plugins/bd-search/';
    }
}

// Mock additional WordPress functions for Algolia tests
if (! function_exists('get_post')) {
    function get_post($post = null)
    {
        return (object) ['ID' => 123, 'post_type' => 'post', 'post_status' => 'publish'];
    }
}

if (! function_exists('get_post_type')) {
    function get_post_type($post = null)
    {
        return 'post';
    }
}

if (! function_exists('get_post_status')) {
    function get_post_status($post = null)
    {
        return 'publish';
    }
}

if (! function_exists('wp_is_post_revision')) {
    function wp_is_post_revision($post)
    {
        return false;
    }
}

if (! function_exists('wp_is_post_autosave')) {
    function wp_is_post_autosave($post)
    {
        return false;
    }
}

if (! function_exists('is_wp_error')) {
    function is_wp_error($thing)
    {
        return false;
    }
}

if (! function_exists('get_post_field')) {
    function get_post_field($field, $post = null, $context = 'display')
    {
        return 'Test content';
    }
}

if (! function_exists('wp_strip_all_tags')) {
    function wp_strip_all_tags($string, $remove_breaks = false)
    {
        return strip_tags($string);
    }
}

// Mock WP_Error class
if (! class_exists('WP_Error')) {
    class WP_Error
    {
        public function __construct($code = '', $message = '', $data = '')
        {
        }
    }
}

// Include Algolia functions for testing
require_once BD616__PLUGIN_DIR . '/inc/algolia/al-scripts/algolia-scripts.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/al-helpers/get_algolia_indexName.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/al-helpers/bd324_get_algolia_index_name.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/al-helpers/algolia_post_index_name.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/updates/update_algolia_record.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/al-records/algolia_add_content_to_record.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/al-records/algolia_add_permalink_to_record.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/al-records/algolia_add_featured_to_record.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/al-records/algolia_add_contributor_to_record.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/al-records/algolia_add_tax_values_to_record.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/al-records/algolia_add_wordpress_post_type_to_record.php';

/**
 * Cleanup after tests
 */
register_shutdown_function(function () {
    Brain\Monkey\tearDown();
});

echo "BD324 debug: Bootstrap loaded successfully\n";
