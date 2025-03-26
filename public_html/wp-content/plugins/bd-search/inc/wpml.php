<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}


function bd324_add_lang_to_index_name( $content ) {
   
   // Add language suffix
   if ($algolia_index_language) {
      $algolia_index_name_suffix = '_' . $algolia_index_language;
      $algolia_index_name = $table_prefix . $algolia_index_name . $algolia_index_name_suffix;
      
   }
   return $algolia_index_name;
}
// add_filter('bd324_filter_index_name', 'bd324_add_lang_to_index_name', 10, 1);