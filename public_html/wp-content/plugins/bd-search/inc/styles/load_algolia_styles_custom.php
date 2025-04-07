<?php

if(!defined('ABSPATH')) {
   die('Invalid request, dude!');
}

/**
 * Load custom styles
 * @since 2.0.0
 */
if (!function_exists('BD616__load_algolia_styles_custom')) :
   function BD616__load_algolia_styles_custom()
   {
      if (!is_admin()) {
         wp_enqueue_style('bd-search-styles-theme', BD616__STYLES_URL .  '/custom/theme/theme.css', '', BD616__PLUGIN_VERSION, 'all');
      }
   }
endif;
add_action('wp_enqueue_scripts', 'BD616__load_algolia_styles_custom');