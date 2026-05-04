<?php // Index scripts: __post_type__

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

function __CONST__register___post_type___scripts()
{
    if (!function_exists('bd324_algolia_register_scripts')) {
        return;
    }

    $handle        = 'algolia-search-__post_type__';
    $config_handle = 'algolia-search-__post_type__-config';
    $url           = __CONST__SCRIPTS_URL . '/__post_type__/' . $handle . '.js';
    $url_config    = __CONST__SCRIPTS_URL . '/__post_type__/' . $config_handle . '.js';

    bd324_algolia_register_scripts(
        $handle,
        $config_handle,
        $url,
        $url_config,
        __CONST__PLUGIN_VERSION
    );
}
add_action('wp_enqueue_scripts', '__CONST__register___post_type___scripts');
