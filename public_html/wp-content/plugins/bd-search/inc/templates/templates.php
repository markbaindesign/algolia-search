<?php // Templates

if (!defined('ABSPATH')) {
   die('Invalid request, dude!');
}

/**
 * Here lives some template parts. 
 */

/**
 * Search Listings
 *
 * A template part to display a search box and a list of results,
 * with filters.
 * 
 * @param $index       The index to search
 */
if(!function_exists('BD616__show_search_listings')):
   function BD616__show_search_listings($index, $handle_script, $handle_config)
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
      $search = apply_filters(
         'BD616__filter_algolia_listings_search', 
         '<div id="algolia-listings__search--' . $index . '" class="searchbox algolia-listings__search algolia-listings__search--'. $index .'"></div>',
         $index
      );
      $output .= $search;
      
      // Stats
      $stats = apply_filters(
         'BD616__filter_algolia_listings_stats', 
         '<div id="algolia-listings__stats--' . $index . '" class="algolia-listings__stats algolia-listings__stats--'. $index .'"></div>',
         $index
      );
      $output .= $stats;

      // Filters
      $filters = apply_filters(
         'BD616__filter_algolia_listings_filters', 
         '<div id="algolia-listings__filters--' . $index . '" class="algolia-listings__filters algolia-filters algolia-listings__filters--'. $index .'"></div>',
         $index
      );
      $output .= $filters;

      // Clear Filters
      $filters_clear = apply_filters(
         'BD616__filter_algolia_listings_filters_clear', 
         '<div id="algolia-listings__filters-clear--' . $index . '" class="algolia-listings__filters-clear algolia-listings__filters-clear--'. $index .'"></div>',
         $index
      );
      $output .= $filters_clear;

      // Current Filters
      $filters_current = apply_filters(
         'BD616__filter_algolia_listings_filters_current', 
         '<div id="algolia-listings__filters-current--' . $index . '" class="algolia-listings__filters-current algolia-listings__filters-current--'. $index .'"></div>',
         $index
      );
      $output .= $filters_current;

      // Hits
      $hits = apply_filters(
         'BD616__filter_algolia_listings_hits', 
         '<div id="algolia-listings__hits--' . $index . '" class="algolia-listings__hits algolia-listings__hits--'. $index .'"></div>',
         $index
      );
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
         'BD616__filter_algolia_listings_template_output', 
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
