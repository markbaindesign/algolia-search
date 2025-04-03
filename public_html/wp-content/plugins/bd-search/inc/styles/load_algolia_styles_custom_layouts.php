<?php

if(!defined('ABSPATH')) {
   die('Invalid request, dude!');
}

/**
 * Load custom layouts
 * @since 2.0.0
 */
if (!function_exists('BD616__load_algolia_styles_custom_layouts')) :
   function BD616__load_algolia_styles_custom_layouts()
   {
      if (!is_admin()) {
         wp_enqueue_style('bd-search-styles-layout', BD616__STYLES_URL .  '/custom/layout/layout.css', '', BD616__PLUGIN_VERSION, 'all');
      }
   }
endif;
add_action('wp_enqueue_scripts', 'BD616__load_algolia_styles_custom_layouts');