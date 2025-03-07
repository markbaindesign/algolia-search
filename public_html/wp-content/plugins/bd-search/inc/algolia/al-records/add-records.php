<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

require_once BD616__PLUGIN_DIR . '/inc/algolia/al-records/al-post-types/add-post-types.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/al-records/al-add-fields/add-fields.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/al-records/al-filters/add-filters.php';

/**
 * Convert post data to Algolia record
 *
 * Filter the post data. Convert to record.
 * All post types.
 * 
 * @param WP_Post $post
 * @return $record
 */
if (!function_exists('bd324_convert_post_data')):
   function bd324_convert_post_data(WP_Post $post)
   {
      $post_id = $post->ID;
      $post_type = get_post_type($post_id);

      /**
       * Pass post data through a generic filter
       * See `add-records/add-all-post-types.php` for filters.
       */
      $record = (array) apply_filters(
         'all_post_types_to_record',
         $post
      );

      /**
       * Add rows to the $record array depending on the post type via filter.
       * See `add-records/add-{{$post_type}}.php` for filters.
       * @param   string   $post_type
       * @return  array    $record
       */
      $filter_name = str_replace('-', '_', $post_type) . '_to_record';
      $record = (array) apply_filters(
         $filter_name,
         $post,
         $record
      );

      /* Check record size does not exceed Algolia Max Record Size */
      $sizeOk = BD616_check_record_size($record, $post_id);
      if ($sizeOk === false) {
         return;
      }
      return $record;
   }
endif;