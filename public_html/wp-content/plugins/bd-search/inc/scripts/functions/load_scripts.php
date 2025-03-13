<?php // 

if (!defined('ABSPATH')) {
   die('Invalid request, dude!');
}

/**
 * Load index scripts
 *
 * functionDescription
 * @param $functionParam       functionDescription
 */
if (!function_exists('bd324_algolia_load_index_scripts')):
   function bd324_algolia_load_index_scripts($script_handle, $config_handle, $script_url, $script_url_config, $plugin_version)
   {
      // Translations Array
      $algolia_translations = array(
         'placeholder_search' => esc_attr__('Search', '_bd_algolia_search_plugin'),
         'label_reset' => esc_attr__('Clear', '_bd_algolia_search_plugin'),
         'label_empty' => esc_attr__('Nothing found', '_bd_algolia_search_plugin'),
         'label_more' => esc_attr__('More', '_bd_algolia_search_plugin'),
         'label_reset_filters' => esc_attr__('Reset filters', '_bd_algolia_search_plugin'),
         'label_no_filters' => esc_attr__('No filters', '_bd_algolia_search_plugin'),
      );

      // Register script
      wp_register_script(
         $script_handle,
         $script_url,
         array('algolia-client', 'algolia-instant-search'),
         $plugin_version,
         array(
            'in_footer' => true,
            'strategy'  => 'defer',
         )
      );

      // Register config
      wp_register_script(
         $config_handle,
         $script_url_config,
         array($script_handle),
         $plugin_version,
         array(
            'in_footer' => true,
            'strategy'  => 'defer',
         )
      );

      wp_localize_script(
         $script_handle,
         'algolia_translations_object',
         $algolia_translations
      );
   }
endif;
