<?php // Shortcodes

if (!defined('ABSPATH')) {
   die('Invalid request, dude!');
}

if (!function_exists('BD616__search_global')):
   function BD616__search_global()
   {
      $content = '';
      wp_enqueue_script('algolia-search-global');
      $content .= '<div id="searchbox--global" class="searchbox searchbox--global searchbox--dropdown"></div>';
      $content .= '<div id="hits--global" class="hits hits--compact"></div>';
      return $content;
   }
endif;

if (!function_exists('BD616__search_menu')):
   // Menu Search
   function BD616__search_menu()
   {
      $content = '';
      wp_enqueue_script('algolia-search-menu');
      $content .= '<div id="searchbox--menu" class="searchbox searchbox--menu searchbox--global searchbox--dropdown"></div>';

      $content .= '<div id="hits--menu" class="hits hits--compact"></div>';
      return $content;
   }
endif;

if (!function_exists('BD616__search_global_echo')):
   function BD616__search_global_echo()
   {
      $content = BD616__search_global();
      echo $content;
   }
endif;

if (!function_exists('BD616__search')):
   // Search shortcode
   function BD616__search()
   {
      ob_start();
      echo BD616__search_global();
      $content = ob_get_clean();
      return $content;
   }
   add_shortcode('search', 'BD616__search');
endif;

if (!function_exists('BD616__searchform_shortcode')):
   /**
    * Search Form Shortcode
    *
    * A search form in a shortcode
    * 
    * @param $att       Shortcode attributes
    */
   function BD616__searchform_shortcode($atts = array())
   {

      // Attributes
      extract(shortcode_atts(array(
         'index' => 'global',
         'template' => ''
      ), $atts));

      // Vars
      $content = '';

      $facets = apply_filters(
         'bd324_filter_search_facets_' . $index,
         array()
      );

      if (isset($atts["index"])) {
         $index = $atts["index"];
      }

      // Enqueue default Algolia Scripts
      algolia_enqueue_default_scripts(); // Vendor

      // Enqueue Index Scripts
      $handle_script = bd324_get_script_handles($index);
      $handle_script_config = bd324_get_script_handles($index, true);
      wp_enqueue_script($handle_script);
      wp_enqueue_script($handle_script_config);

      ob_start();
      echo bd324_show_advanced_search_template($index);

      $content =  ob_get_contents();
      ob_clean();
      return $content;
   }
   add_shortcode('bd-search', 'BD616__searchform_shortcode');
endif;

// Allow shortcodes in widgets
add_filter('widget_text', 'do_shortcode');
