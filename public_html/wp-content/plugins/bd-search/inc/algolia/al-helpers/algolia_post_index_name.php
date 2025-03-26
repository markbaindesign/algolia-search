<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

if(!function_exists('algolia_post_index_name')):
   /**
    * Customizing the Algolia index name
    */
   function algolia_post_index_name($defaultName)
   {
      global $table_prefix;
   
      return $table_prefix . $defaultName;
   }
   add_filter('algolia_index_name', 'algolia_post_index_name');
endif;