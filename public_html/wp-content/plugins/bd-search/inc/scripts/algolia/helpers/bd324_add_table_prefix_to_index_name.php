<?php // 

if(!defined('ABSPATH')) {
   die('Invalid request, dude!');
}

/**
 * Add the table prefix to the index name
 *
 * @param string $content       Algolia index name
 */
function bd324_add_table_prefix_to_index_name($content)
{
   // Get Table Prefix
   global $table_prefix;

   if ($table_prefix) {
      $content = $table_prefix . $content;
   }
   return $content;
}
add_filter('bd324_filter_index_name', 'bd324_add_table_prefix_to_index_name', 20, 1);
