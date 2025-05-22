<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

if(!function_exists('bd324_get_post_types_for_index')):
   /**
    * Get Post Types for Index
    * 
    * @param   string   $index_name    Generic name of index
    *                                  without prefix or suffix.
    * @return  array    $post_types    Array of post types to 
    *                                  add to index. Defaults to all 
    *                                  public post types. 
    */
   function bd324_get_post_types_for_index($index_name)
   {

      // Filter post types
      $post_types = apply_filters(
         'bd324_filter_get_post_types_for_index_' . $index_name,
         [
            'post',
            'page',
         ]
      );
      return $post_types;
   }
endif;