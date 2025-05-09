<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude!');
}

/**
 * Update global index when post saved
 * All post types
 */
function algolia_update_global_index($id, WP_Post $post, $update)
{
   global $algolia;

   if (wp_is_post_revision($id) || wp_is_post_autosave($id)) {
      return $post;
   }

   $post_id = $post->ID;
   $post_type = get_post_type($post_id);

   /**
    * Add rows to the $record array depending on the post type via filter.
    * See `add-records/add-{{$post_type}}.php` for filters.
    * @param   string   $post_type
    * @return  array    $record
    */
   $filter_name = str_replace('-', '_', $post_type) . '_to_record';
   $record = (array) apply_filters($filter_name, $post);

   /* Check record size does not exceed Algolia Max Record Size */
   if(function_exists('BD616_check_record_size')):
      $sizeOk = BD616_check_record_size($record, $post_id);
      if ($sizeOk === false) {
         return $post;
      }
   endif;

   if (!isset($record['objectID'])) {
      $record['objectID'] = implode('#', [$post->post_type, $post->ID]);
   }

   /* Get the index */
   $index_name = apply_filters('algolia_index_name', 'global');
   $index = $algolia->initIndex(
      $index_name
   );

   /* Set index config
    * ================
    * For now, not doing this in code,
    * do via the web interface.
    */
   algolia_index_config($index, $index_name);

   // Trash or save
   if ('trash' == $post->post_status || !empty($post->post_password)) {
      $index->deleteObject($record['objectID']);
      $action = 'Deleted';
   } else {
      $index->saveObject($record);
      $action = 'Updated';
   }

   return $post;
}
// add_action('save_post', 'algolia_update_global_index', 10, 3);