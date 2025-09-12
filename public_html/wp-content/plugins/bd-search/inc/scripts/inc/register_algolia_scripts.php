<?php // 

if (!defined('ABSPATH')) {
   die('Invalid request, dude!');
}

/**
 * Load index scripts
 */
if (!function_exists('bd324_algolia_register_scripts')):
   function bd324_algolia_register_scripts($script_handle, $config_handle, $script_url, $script_url_config, $plugin_version)
   {

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

      $script_handle_underscores = str_replace('-', '_', $script_handle);
      wp_localize_script(
         $script_handle,
         'translations_object_' . $script_handle_underscores,
         bd324_get_algolia_translation($script_handle_underscores)
      );
   }
endif;
