<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}
/**
 * Register Advanced Global script
 */
if (!function_exists('bd324_register_algolia_script_global_advanced')):
   function bd324_register_algolia_script_global_advanced()
   {
      $script_handle = 'algolia-search-global-advanced';
      $config_handle = 'algolia-search-global-advanced-config';
      $script_url = BD616__SCRIPTS_URL . '/custom/algolia/global/advanced/' . $script_handle . '.js';
      $script_url_config = BD616__SCRIPTS_URL . '/custom/algolia/global/advanced/' . $config_handle . '.js';
      $plugin_version = BD616__PLUGIN_VERSION;

      // Register scripts
      if (function_exists('bd324_algolia_register_scripts')):
         bd324_algolia_register_scripts($script_handle, $config_handle, $script_url, $script_url_config, $plugin_version);
      endif;
   }
endif;
add_action('wp_enqueue_scripts', 'bd324_register_algolia_script_global_advanced');
