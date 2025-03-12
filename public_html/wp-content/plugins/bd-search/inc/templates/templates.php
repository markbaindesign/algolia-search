<?php // Templates

if (!defined('ABSPATH')) {
   die('Invalid request, dude!');
}

require_once 'helpers/helpers.php';

/**
 * Search Listings
 *
 * A template part to display a search box and a list of results,
 * with filters.
 * 
 * @param $index       The index to search
 */
if(!function_exists('BD616__show_search_listings')):
   function BD616__show_search_listings($index, $handle_script, $handle_config, $template_name)
   {
      $output = '';

      // Enqueue default Algolia scripts
      if( function_exists('algolia_enqueue_default_scripts')){
         algolia_enqueue_default_scripts();
      }

      if($handle_script){
         wp_enqueue_script($handle_script);
      }

      if($handle_config){
         wp_enqueue_script($handle_config);
      }

      // Open Wrapper
      $wrapper_open = apply_filters(
         'BD616__filter_algolia_listings_wrapper_open', 
         '<!-- Start Search Listings --><div class="search-wrapper search-wrapper--listings search-wrapper--' . $index . '">',
         $index
      );
      $output .= $wrapper_open;

      // Search
      $search = bd324_get_algolia_template_part('searchbox', $index, $template_name);
      $output .= $search;
      
      // Stats
      $stats = bd324_get_algolia_template_part('stats', $index, $template_name);
      $output .= $stats;

      // Filters
      $filters = bd324_get_algolia_template_part('filters', $index, $template_name);
      $output .= $filters;

      // Clear Filters
      $filters_clear = bd324_get_algolia_template_part('filters_clear', $index, $template_name);
      $output .= $filters_clear;

      // Current Filters
      $filters_current = bd324_get_algolia_template_part('filters_current', $index, $template_name);
      $output .= $filters_current;

      // Hits
      $hits = bd324_get_algolia_template_part('hits', $index, $template_name);
      $output .= $hits;

      // Close Wrapper
      $wrapper_close = apply_filters(
         'BD616__filter_algolia_listings_wrapper_close', 
         '</div><!-- End Search Listings -->',
         $index
      );
      $output .= $wrapper_close;

      // Output
      // Filter allows reordering/removal of elements in template
      echo apply_filters(
         'BD616__filter_algolia_template_output_' . $index . '_' . $template_name, 
         $output,
         $index,
         $wrapper_open,
         $search,
         $stats,
         $filters,
         $filters_clear,
         $filters_current,
         $hits,
         $wrapper_close 
      );
   }
endif;
