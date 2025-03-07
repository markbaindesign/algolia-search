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
   // Environment Check
   if (!defined('WP_ENVIRONMENT_TYPE')) {
      return $post;
   }

   error_log(print_r("Running algolia_update_global_index", true));

   if (wp_is_post_revision($id) || wp_is_post_autosave($id)) {
      return $post;
   }

   global $algolia;
   $post_id = $post->ID;
   $post_type = get_post_type($post_id);

   $record = (array) apply_filters(str_replace('-', '_', $post_type) . '_to_record', $post);

   /* Check record size does not exceed Algolia Max Record Size */
   $sizeOk = BD616_check_record_size($record, $post_id);
   if ($sizeOk === false) {
      return $post;
   }

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