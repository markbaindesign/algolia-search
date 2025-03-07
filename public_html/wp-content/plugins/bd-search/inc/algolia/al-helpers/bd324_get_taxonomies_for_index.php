<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

/**
 * Get Taxonomies for Index
 * 
 * @param   string   $index_name    Generic name of index
 *                                  without prefix or suffix.
 * @return  array    $taxonomies    Array of taxonomies to 
 *                                  add to index.
 */
function bd324_get_taxonomies_for_index($index_name)
{
   $taxonomies = array();
   if ($index_name === 'global') {
      $taxonomies = array(
         'grant_category',
         'awards-prizes',
      );
   }
   return $taxonomies;
}
