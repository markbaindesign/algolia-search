<?php

// Algolia Scripts

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

function algolia_load_assets()
{

   // Get current language
   $active_language = array(
      'active_language' => apply_filters('wpml_current_language', NULL),
   );

   // Translations Array
   $algolia_translations = array(
      'placeholder_search' => esc_attr__(
         apply_filters(
            'bd324_filter_search_input_placeholder',
            __('Search', '_bd_algolia_search_plugin')
         )
      ),
      'label_reset' => esc_attr__('Clear', '_bd_algolia_search_plugin'),
      'label_empty' => esc_attr__('Nothing found', '_bd_algolia_search_plugin'),
      'label_more' => esc_attr__('More', '_bd_algolia_search_plugin'),
   );

   // Enqueue Algolia
   wp_enqueue_script(
      'algolia',
      'https://cdn.jsdelivr.net/npm/algoliasearch@4.17.0/dist/algoliasearch-lite.umd.js',
      array(),
      BD616__PLUGIN_VERSION,
      true
   );

   // Enqueue Instantsearch
   wp_enqueue_script(
      'algolia-instant-search',
      'https://cdn.jsdelivr.net/npm/instantsearch.js@4.55.0/dist/instantsearch.production.min.js',
      array('algolia'),
      BD616__PLUGIN_VERSION,
      array(
         'in_footer' => true,
         'strategy'  => 'defer',
      )
   );

   // Enqueue Client
   wp_enqueue_script(
      'algolia-client',
      BD616__SCRIPTS_URL . '/custom/algolia/algolia-search-client.js',
      array('algolia-instant-search'),
      BD616__PLUGIN_VERSION,
      array(
         'in_footer' => true,
         'strategy'  => 'defer',
      )
   );

   // Enqueue Modal Script
   wp_enqueue_script(
      'search-modal',
      BD616__SCRIPTS_URL . '/custom/modal.js',
      array(),
      BD616__PLUGIN_VERSION,
      array(
         'in_footer' => true,
         'strategy'  => 'defer',
      )
   );

   // Enqueue Modal Script Config
   wp_enqueue_script(
      'search-modal-config',
      BD616__SCRIPTS_URL . '/custom/modal-config.js',
      array('search-modal'),
      BD616__PLUGIN_VERSION,
      array(
         'in_footer' => true,
         'strategy'  => 'defer',
      )
   );

   // Modal trigger class
   $trigger_modal = apply_filters(
      'bd324_filter_modal_trigger',
      '.bd-search-modal-trigger' // default trigger
   );

   wp_localize_script(
      'search-modal-config',
      'trigger_modal',
      $trigger_modal
   );

   // Localize script
   $algolia_constants = array(
      'app'          => ALGOLIA_APPLICATION_ID,
      'search_key'   => ALGOLIA_SEARCH_API_KEY
   );

   wp_localize_script(
      'algolia-client',
      'algolia_vars',
      $algolia_constants
   );

   wp_localize_script(
      'algolia-search-global', // handle
      'algolia_translations_object', // 
      $algolia_translations
   );

   // Localize Team script to add
   // active language
   wp_localize_script(
      'algolia-team-search', // handle
      'algolia_active_lang_object', // 
      $active_language
   );
}
add_action('wp_enqueue_scripts', 'algolia_load_assets');

function algolia_enqueue_default_scripts()
{
   wp_enqueue_script('algolia');
   wp_enqueue_script('algolia-instant-search');
   wp_enqueue_script('algolia-client');
}
