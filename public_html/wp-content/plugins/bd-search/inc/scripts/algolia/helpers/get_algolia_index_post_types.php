<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

/**
 * Get Algolia Index post types
 * @return array $post_types
 * 
 */
function get_algolia_index_post_types($index_name)
{
   // Refactor
   $post_types = bd324_get_post_types_for_index($index_name);
   return $post_types;
}