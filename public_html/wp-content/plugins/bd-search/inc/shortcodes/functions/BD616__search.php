<?php // 

if(!defined('ABSPATH')) {
   die('Invalid request, dude!');
}

// Search shortcode
/**
 * No idea if this is used or not!
 */
if (!function_exists('BD616__search')):
   function BD616__search()
   {
      ob_start();
      echo BD616__search_global();
      $content = ob_get_clean();
      return $content;
   }
   add_shortcode('search', 'BD616__search');
endif;