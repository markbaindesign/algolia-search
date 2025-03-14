<?php // 

if (!defined('ABSPATH')) {
   die('Invalid request, dude!');
}

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
         'template' => 'shortcode'
      ), $atts));

      // Vars
      $content = '';
      $index = 'global';
      $template = '';

      $facets = apply_filters(
         'bd324_filter_search_facets_' . $index,
         array()
      );

      if (isset($atts["index"])) {
         $index = $atts["index"];
      }

      if (isset($atts["template"])) {
         $template = $atts["template"];
      }

      ob_start();
      echo bd324_show_advanced_search_template($index, $template);

      $content =  ob_get_contents();
      ob_clean();
      return $content;
   }
   add_shortcode('bd-search', 'BD616__searchform_shortcode');
endif;
