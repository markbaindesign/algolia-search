<?php

if(!defined('ABSPATH')) {
   die('Invalid request, dude!');
}

/**
 * Search Modal Overlay
 */
if (!function_exists('BD616__search_modal_overlay')) :
   function BD616__search_modal_overlay()
   {
      echo '<div id="search_modal_overlay" class="search-modal-overlay"></div>';
   }
endif;
add_action('wp_footer', 'BD616__search_modal_overlay');