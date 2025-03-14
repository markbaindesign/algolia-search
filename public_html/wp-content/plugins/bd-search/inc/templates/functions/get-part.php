<?php // Searchbox

if (!defined('ABSPATH')) {
   die('Invalid request, dude!');
}

/**
 * Searchbox template part
 *
 * @param $index_name      Index name
 * @param $template_name   Template name
 */
if (!function_exists('bd324_get_algolia_template_part')):
   function bd324_get_algolia_template_part($template_part_name, $index_name, $template_name = '')
   {

      $template_id = bd324_get_algolia_template_part_id($template_part_name, $index_name, $template_name);

      $template_classes = bd324_get_algolia_template_part_classes($template_part_name, $index_name, $template_name);

      $template_part_filter_handle = 'BD616__filter_algolia_template_part_' . $template_id;

      $output = apply_filters(
         $template_part_filter_handle,
         '<div id="' . $template_id . '" class="' . $template_classes . '"></div>',
         $index_name
      );

      return $output;
   }
endif;
