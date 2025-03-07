<?php

// Register Algolia Script for Global

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}
/**
 * Register Resources script
 */
if(!function_exists('bd324_register_algolia_script_global')):
   function bd324_register_algolia_script_global()
   {
      
      // Translations Array
      $algolia_translations = array(
         'placeholder_search' => esc_attr__('Search', '_bd_algolia_search_plugin'),
         'label_reset' => esc_attr__('Clear', '_bd_algolia_search_plugin'),
         'label_empty' => esc_attr__('Nothing found', '_bd_algolia_search_plugin'),
         'label_more' => esc_attr__('More', '_bd_algolia_search_plugin'),
      );
      
      wp_enqueue_script(
         'algolia-search-global',
         BD616__SCRIPTS_URL . '/custom/algolia/global/algolia-search-global.js',
         array('algolia-client', 'algolia-instant-search'),
         BD616__PLUGIN_VERSION,
         array(
            'in_footer' => true,
            'strategy'  => 'defer',
         )
      );

      wp_enqueue_script(
         'algolia-search-global-config',
         BD616__SCRIPTS_URL . '/custom/algolia/global/algolia-search-global__config.js',
         array('algolia-client', 'algolia-instant-search', 'algolia-search-global'),
         BD616__PLUGIN_VERSION,
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
add_action('wp_enqueue_scripts', 'bd324_register_algolia_script_global');
