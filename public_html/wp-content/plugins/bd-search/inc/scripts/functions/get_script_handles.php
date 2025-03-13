<?php // 

if (!defined('ABSPATH')) {
   die('Invalid request, dude!');
}

/**
 * Get script handles
 *
 * Helper function to get script handles
 * @param $index_name       The index name
 * @param $is_config       Is this a config script
 */
if (!function_exists('bd324_get_script_handles')):
   function bd324_get_script_handles($index_name, $is_config = false)
   {
      /* Vars */
      $output = '';
      $handle_script = 'algolia-search-' . $index_name;
      $handle_script_config = 'algolia-search-' . $index_name . '-config';
      if ($is_config) {
         $output = $handle_script_config;
      } else {
         $output = $handle_script;
      }

      $output = apply_filters(
         'bd324_filter_handle_script_' . $index_name,
         $output
      );

      return $output;
   }
endif;
