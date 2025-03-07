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
    *                                  add to index.
    */
   function bd324_get_post_types_for_index($index_name)
   {
      /* Get post types for indexing */
      $args = array(
         'public'   => true,
         // '_builtin' => false,
         'exclude_from_search' => false
      );
      $output = 'names';
      $post_types = apply_filters(
         'bd324_filter_get_post_types_for_index',
         get_post_types($args, $output)
      );
      error_log(print_r($post_types, true));

      return $post_types;
   }
endif;