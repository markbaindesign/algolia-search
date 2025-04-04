<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude!');
}

/**
 * Generates query arguments for Algolia indexing.
 *
 * This function builds and filters the query arguments used for fetching posts
 * to be indexed in Algolia. It allows customization of the query parameters
 * through WordPress filters.
 *
 * @param string $algolia_index_name      The name of the Algolia index.
 * @param string $algolia_index_language  The language of the Algolia index.
 * @param array  $post_types              An array of post types to include in the query.
 * @param array  $post_ids                Optional. An array of specific post IDs to include in the query. Default is an empty array.
 * @param int    $paged                   Optional. The page number for paginated queries. Default is 1.
 *
 * @return array The query arguments array.
 *
 * @hook bd324_filter_posts_per_page       Filters the number of posts per page. Default is 300.
 * @hook bd324_filter_post_status          Filters the post status for the query. Default is 'publish'.
 * @hook bd324_filter_has_password         Filters whether to include posts with passwords. Default is false.
 * @hook bd324_filter_query_args_for_index_{algolia_index_name}
 *       Filters the final query arguments before indexing.
 *       Passes the arguments, index name, and index language.
 *
 * @since 1.0.0
 */
if (!function_exists('bd324_get_args_for_query')):
   function bd324_get_args_for_query(
      $algolia_index_name,
      $algolia_index_language,
      $post_types,
      $post_ids = [],
      $paged = 1
   )
   {

      // Set the number of posts per page for the query
      $posts_per_page = apply_filters(
         'bd324_filter_posts_per_page',
         300,
         $algolia_index_name,
         $algolia_index_language
      );

      // Set the post status for the query
      $post_status = apply_filters(
         'bd324_filter_post_status',
         'publish',
         $algolia_index_name,
         $algolia_index_language
      );

      // Set whether to include posts with passwords in the query
      $has_password = apply_filters(
         'bd324_filter_has_password',
         false,
         $algolia_index_name,
         $algolia_index_language
      );


      // Build the query arguments
      $args = [
         'posts_per_page' => $posts_per_page,
         'paged'          => $paged,
         'post_type'      => $post_types,
         'post_status'    => $post_status,
         //'post__in'       => $post_ids,
         'has_password'   => $has_password,
      ];

      /**
       * Filter args before indexing
       * @since 1.0.0
       * @param array $args
       * @param string $algolia_index_name
       * @param string $algolia_index_language
       * @return array $args
       */
      $args = apply_filters(
         'bd324_filter_query_args_for_index_' . $algolia_index_name,
         $args,
         $algolia_index_name,
         $algolia_index_language
      );

      return $args;
   }
endif;
