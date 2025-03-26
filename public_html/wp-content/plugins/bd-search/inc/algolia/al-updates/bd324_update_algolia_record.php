<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude!');
}

/**
 * Update Algolia Record
 * 
 * Updates record in Algolia (all fields, all post types).
 * 
 * @param $post_id
 * @param WP_Post
 * @param $post
 * 
 */
function bd324_update_algolia_record($post_id, WP_Post $post)
{
   global $algolia;

   if (!$post) {
      $post = get_post();
   }
   $post_type = get_post_type($post_id);
   $post_status = get_post_status($post_id);

   /**
    * Don't update record if this is a revision or autosave
    */
   if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) {
      return;
   }


   // Convert post data to Algolia record
   $record = bd324_convert_post_data($post);

   // Get an array of index names
   // ***************************
   // Add filter to include project-specific
   // index names.
   $index_names = apply_filters(
      'bd324_filter_index_names',
      array('global'), // Default
      $post_type
   );

   /**
    * Loop over the array of indices
    */
   foreach ($index_names as $name) {

      // Get the full name of the index (with prefix)
      $nameFull = apply_filters('bd324_filter_index_name', $name);

      $index = $algolia->initIndex($nameFull);

      // Remove any drafts or password-protected posts from the index
      if ('publish' !== $post_status || !empty($post->post_password)) {
         $index->deleteObject($record['objectID']);
      } else {
         $index->saveObject($record);
      }
   }

   return;
}
add_action('save_post', 'bd324_update_algolia_record', 10, 3);