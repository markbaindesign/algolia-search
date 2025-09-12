<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

/**
 * Add Breadcrumbs
 */
function algolia_add_breadcrumbs_to_record($record, $post_id)
{
   $breadcrumbs = '';
   if(function_exists('bd324_get_breadcrumbs_array')):
      $breadcrumbs = bd324_get_breadcrumbs_array($post_id);
   endif;
   if ($breadcrumbs) {
      $crumbs = array();
      $crumbs_string = '';
      foreach ($breadcrumbs as $breadcrumb) {
         $crumbs[] = $breadcrumb['label'];
      }
      $crumbs_string = implode(' / ', $crumbs);
      $record['breadcrumbs'] = $crumbs_string;
   }
   return $record;
}
add_filter('add_breadcrumbs_to_record', 'algolia_add_breadcrumbs_to_record', 10, 2);