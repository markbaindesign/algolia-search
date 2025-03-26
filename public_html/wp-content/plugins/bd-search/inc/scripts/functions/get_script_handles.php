<?php // 

if (!defined('ABSPATH')) {
   die('Invalid request, dude!');
}

/**
 * Get script handles
 *
 * Helper function to get script handles
 * @param $index_name       The index name
 * @param $template_name       The template name
 * @param $is_config       Is this a config script
 */
if (!function_exists('bd324_get_script_handles')):
   function bd324_get_script_handles($index_name, $template_name = '', $is_config = false)
   {
      $output = '';
      $handle = 'algolia-search-' . $index_name;
      if ($template_name) {
         $handle .= '-' . $template_name;
      }
      if ($is_config) {
         $handle .= '-config';
      }
      $output = apply_filters(
         'bd324_filter_handle_script_' . $handle,
         $handle
      );
      return $output;
   }
endif;
