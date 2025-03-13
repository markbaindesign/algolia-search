<?php

// Register Algolia Script for Global

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}
/**
 * Register Global script
 */
if(!function_exists('bd324_register_algolia_script_global')):
   function bd324_register_algolia_script_global()
   {
      // Vars
      $script_handle = 'algolia-search-grantings';
      $config_handle = 'algolia-search-grantings-config';
      $script_url = BD8090__SCRIPTS_URL . '/list-grantings/' . $script_handle . '.js';
      $script_url_config = BD8090__SCRIPTS_URL . '/list-grantings/' . $config_handle . '.js';
      $plugin_version = BD616__PLUGIN_VERSION;
      
      $script_url = apply_filters(
         'bd324_filter_register_algolia_script_global_script_url', BD616__SCRIPTS_URL . '/custom/algolia/global/algolia-search-global.js',
      );

      $script_url_config = apply_filters(
         'bd324_filter_register_algolia_script_global_script_url_config', BD616__SCRIPTS_URL . '/custom/algolia/global/algolia-search-global-config.js',
      );

      $plugin_version = apply_filters(
         'bd324_filter_register_algolia_script_global_plugin_version', BD616__PLUGIN_VERSION,
      );

      // Translations Array
      $algolia_translations = apply_filters(
         'bd324_filter_register_algolia_script_global_translations',
         array(
            'placeholder_search' => esc_attr__('Search', '_bd_algolia_search_plugin'),
            'label_reset' => esc_attr__('Clear', '_bd_algolia_search_plugin'),
            'label_empty' => esc_attr__('Nothing found', '_bd_algolia_search_plugin'),
            'label_more' => esc_attr__('More', '_bd_algolia_search_plugin'),
            'label_reset_filters' => esc_attr__('Reset filters', '_bd_algolia_search_plugin'),
            'label_no_filters' => esc_attr__('No filters', '_bd_algolia_search_plugin'),
         )
      );
      
      wp_enqueue_script(
         'algolia-search-global',
         $script_url,
         array('algolia-client', 'algolia-instant-search'),
         $plugin_version,
         array(
            'in_footer' => true,
            'strategy'  => 'defer',
         )
      );

      wp_enqueue_script(
         'algolia-search-global-config',
         $script_url_config,
         array('algolia-client', 'algolia-instant-search', 'algolia-search-global'),
         $plugin_version,
         array(
            'in_footer' => true,
            'strategy'  => 'defer',
         )
      );

      wp_localize_script(
         'algolia-search-global',
         'algolia_translations_object',
         $algolia_translations
      );

   }
endif;
// add_action('wp_enqueue_scripts', 'bd324_register_algolia_script_global');
