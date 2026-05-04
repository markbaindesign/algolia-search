<?php // Index scripts: global

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

function __CONST__register_global_scripts()
{
    // Replace the default core global script with the project-specific one.
    remove_action('wp_enqueue_scripts', 'bd324_register_algolia_script_global');

    if (!function_exists('bd324_algolia_register_scripts')) {
        return;
    }

    $handle        = 'algolia-search-global';
    $config_handle = 'algolia-search-global-config';
    $url           = __CONST__SCRIPTS_URL . '/global/' . $handle . '.js';
    $url_config    = __CONST__SCRIPTS_URL . '/global/' . $config_handle . '.js';

    bd324_algolia_register_scripts(
        $handle,
        $config_handle,
        $url,
        $url_config,
        __CONST__PLUGIN_VERSION
    );
    wp_enqueue_script($handle);
}
add_action('wp_enqueue_scripts', '__CONST__register_global_scripts');
