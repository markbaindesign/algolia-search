<?php

/**
 * Memory diagnostic tool - add this to functions.php temporarily
 *
 * This will log memory usage at key WordPress execution points
 */

// Log memory at WordPress init
add_action('init', function () {
    $memory = round(memory_get_usage(true) / 1024 / 1024, 2);
    error_log("MEMORY DIAGNOSTIC: WordPress init - Using: {$memory}MB");
});

// Log memory when plugins are loaded
add_action('plugins_loaded', function () {
    $memory = round(memory_get_usage(true) / 1024 / 1024, 2);
    error_log("MEMORY DIAGNOSTIC: Plugins loaded - Using: {$memory}MB");
});

// Log memory when theme is setup
add_action('after_setup_theme', function () {
    $memory = round(memory_get_usage(true) / 1024 / 1024, 2);
    error_log("MEMORY DIAGNOSTIC: Theme setup - Using: {$memory}MB");
});

// Log memory on wp_loaded (after everything is loaded)
add_action('wp_loaded', function () {
    $memory = round(memory_get_usage(true) / 1024 / 1024, 2);
    error_log("MEMORY DIAGNOSTIC: WP fully loaded - Using: {$memory}MB");
});

// Log memory before our specific conversion function runs
add_filter('bd324_before_convert_post', function ($post) {
    $memory = round(memory_get_usage(true) / 1024 / 1024, 2);
    error_log("MEMORY DIAGNOSTIC: Before converting Post #{$post->ID} - Using: {$memory}MB");
    return $post;
}, 5);

// Log what plugins are active and memory when admin loads
add_action('admin_init', function () {
    static $logged = false;
    if (!$logged) {
        $memory = round(memory_get_usage(true) / 1024 / 1024, 2);
        $active_plugins = get_option('active_plugins', []);
        $plugin_count = count($active_plugins);
        error_log("MEMORY DIAGNOSTIC: Admin init - Using: {$memory}MB");
        error_log("MEMORY DIAGNOSTIC: {$plugin_count} active plugins: " . implode(', ', array_slice($active_plugins, 0, 10)));
        $logged = true;
    }
});
