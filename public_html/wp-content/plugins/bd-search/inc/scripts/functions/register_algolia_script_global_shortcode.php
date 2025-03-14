<?php

// Register Algolia Script for Global

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}
/**
 * Register Resources script
 */
if (!function_exists('bd324_register_algolia_script_global_shortcode')):
   function bd324_register_algolia_script_global_shortcode()
   {
      $script_handle = 'algolia-search-global-shortcode';
      $config_handle = 'algolia-search-global-shortcode-config';
      $script_url = BD616__SCRIPTS_URL . '/custom/algolia/global/shortcode/' . $script_handle . '.js';
      $script_url_config = BD616__SCRIPTS_URL . '/custom/algolia/global/shortcode/' . $config_handle . '.js';
      $plugin_version = BD616__PLUGIN_VERSION;

      // Register scripts
      if (function_exists('bd324_algolia_register_scripts')):
         bd324_algolia_register_scripts($script_handle, $config_handle, $script_url, $script_url_config, $plugin_version);
      endif;
   }
endif;
add_action('wp_enqueue_scripts', 'bd324_register_algolia_script_global_shortcode');
