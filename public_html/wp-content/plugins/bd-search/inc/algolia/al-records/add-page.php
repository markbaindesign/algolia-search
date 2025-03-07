<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

/**
 * Convert Page data to Algolia record
 */
if(!function_exists('algolia_page_to_record')):
   function algolia_page_to_record(WP_Post $post)
   {
      $post_id = $post->ID;

      $record = [
         'postID'       => $post_id,
         'objectID'     => implode('#', [$post->post_type, $post_id]),
         'title'        => strip_tags($post->post_title),
         'excerpt'      => $post->post_excerpt,
         'content'      => strip_shortcodes(strip_tags($post->post_content)),
         'image'        => get_the_post_thumbnail_url($post_id),
      ];

      $record = apply_filters('add_breadcrumbs_to_record',           $record, $post_id);
      $record = apply_filters('add_permalink_to_record',             $record, $post_id);
      $record = apply_filters('add_wordpress_post_type_to_record',   $record, $post_id);
      $record = apply_filters('add_acf_content_to_record',           $record, $post_id);

      return $record;
   }
endif;
add_filter('page_to_record', 'algolia_page_to_record');