<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

/**
 * Check post is allowed
 * @since 1.0.0
 * @package BD616
 *
 * Pluggable function to allow post filtering.
 * @param int    $post_id            The ID of the post to check.
 * @param string $post_type          The type of the post.
 * @param string $algolia_index_name The name of the Algolia index.
 */
if(!function_exists('BD616__is_post_allowed')):
   function BD616__is_post_allowed($post_id, $post_type, $algolia_index_name)
   {
      // TO DO: add filter here
      return true;
   }
endif;