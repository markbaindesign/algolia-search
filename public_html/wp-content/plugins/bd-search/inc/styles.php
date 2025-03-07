<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

/**
 * Load vendor styles
 * @since 2.0.0
 */

if (!function_exists('BD616__load_algolia_styles_vendor')) :
   function BD616__load_algolia_styles_vendor()
   {
      if (!is_admin()) {
         wp_enqueue_style('algolia-reset', '//cdn.jsdelivr.net/npm/instantsearch.css@7.3.1/themes/reset.css', '', BD616__PLUGIN_VERSION, 'all');
         wp_enqueue_style('algolia-theme', '//cdn.jsdelivr.net/npm/instantsearch.css@7.4.5/themes/satellite.css', '', BD616__PLUGIN_VERSION, 'all');
      }
   }
endif;
add_action('wp_enqueue_scripts', 'BD616__load_algolia_styles_vendor');

/**
 * Load custom styles
 * @since 2.0.0
 */
if (!function_exists('BD616__load_algolia_styles_custom')) :
   function BD616__load_algolia_styles_custom()
   {
      if (!is_admin()) {
         wp_enqueue_style('search-styles', BD616__STYLES_URL .  '/custom/styles.css', '', BD616__PLUGIN_VERSION, 'all');
      }
   }
endif;
add_action('wp_enqueue_scripts', 'BD616__load_algolia_styles_custom');
