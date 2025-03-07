<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

if(!function_exists('bd324_get_algolia_index_name')):
   /**
    * Get full name of index, inc. language
    * 
    * @param   string   $indexName
    * @param   string   $indexLang
    */
   function bd324_get_algolia_index_name($indexName, $indexLang = NULL)
   {
      $parts = [];
   
      if (!empty($indexName)) {
         $parts[] = $indexName;
      }
      if (!empty($indexLang)) {
         $parts[] = $indexLang;
      }
      $indexFullName = apply_filters('algolia_index_name', implode('_', $parts));
   
      return $indexFullName;
   }
endif;