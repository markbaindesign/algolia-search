<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

if(!function_exists('bd324_algolia_get_full_index_name')):
/**
 * Get the full Algolia index name
 */
function bd324_algolia_get_full_index_name(
   $algolia_index_name,
   $algolia_index_language = ''
) {
   $algolia_index_name_suffix = '';
   global $table_prefix;
   if ($algolia_index_language) {
      $algolia_index_name_suffix = '_' . $algolia_index_language;
   }
   return $table_prefix . $algolia_index_name . $algolia_index_name_suffix;
}
add_filter('bd324_get_full_index_name', 'bd324_algolia_get_full_index_name', 10, 2);
endif;
