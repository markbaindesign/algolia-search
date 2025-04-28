<?php // Templates

if (!defined('ABSPATH')) {
   die('Invalid request, dude!');
}

/**
 * Search Listings
 *
 * A template part to display a search box and a list of results,
 * with filters.
 * 
 * @param $search_index       The index to search
 * @param $template_name       The template name
 */
if (!function_exists('bd324_show_search_template')):
   function bd324_show_search_template($search_index, $template_name = '')
   {
      $output = '';

      // Enqueue default Algolia scripts
      if (function_exists('algolia_enqueue_default_scripts')) {
         algolia_enqueue_default_scripts();
      }

      // Enqueue Index Scripts
      $handle_script = bd324_get_script_handles($search_index, $template_name);
      bd324_enqueue_script_if_registered($handle_script);

      $handle_script_config = bd324_get_script_handles($search_index, $template_name, true);
      bd324_enqueue_script_if_registered($handle_script_config);

      // Open Wrapper
      $wrapper_open = apply_filters(
         'BD616__filter_algolia_template_wrapper' . $search_index . '_' . $template_name,
         '<div id="ais-wrapper" class="bd-search search__wrapper search-wrapper search-fullpage search-wrapper--listings search-wrapper--' . $search_index . ' search-wrapper--' . $template_name . '">',
         $search_index
      );
      $output .= $wrapper_open;

      // Start Template Part - Header
      $output .= apply_filters(
         'BD616__filter_algolia_template_header' . $search_index . '_' . $template_name,
         '<header id="ais-header" class="search__header">',
         $search_index
      );

      // Search
      $search = bd324_get_algolia_template_part('searchbox', $search_index, $template_name);
      $output .= $search;

      $output .= apply_filters(
         'BD616__filter_algolia_template_header_close' . $search_index . '_' . $template_name,
         '</header>',
         $search_index
      );
      // End Template Part - Aside

      // Start Template Part - Aside
      $output .= apply_filters(
         'BD616__filter_algolia_template_aside' . $search_index . '_' . $template_name,
         '<nav id="ais-aside" class="search__aside">',
         $search_index
      );

      // Stats
      $stats = bd324_get_algolia_template_part('stats', $search_index, $template_name);
      $output .= $stats;

      // Clear Filters
      $filters_clear = bd324_get_algolia_template_part('filters_clear', $search_index, $template_name);
      $output .= $filters_clear;

      // Filters
      $filters = bd324_get_algolia_template_part('filters', $search_index, $template_name);
      $output .= $filters;

      $output .= apply_filters(
         'BD616__filter_algolia_template_aside_close' . $search_index . '_' . $template_name,
         '</nav>',
         $search_index
      );
      // End Template Part - Aside

      // Start Template Part - Main
      $output .= apply_filters(
         'BD616__filter_algolia_template_main' . $search_index . '_' . $template_name,
         '<main id="ais-main" class="search__main">',
         $search_index
      );

      // Current Filters
      $filters_current = bd324_get_algolia_template_part('filters_current', $search_index, $template_name);
      $output .= $filters_current;

      // Hits
      $hits = bd324_get_algolia_template_part('hits', $search_index, $template_name);
      $output .= $hits;

      $output .= apply_filters(
         'BD616__filter_algolia_template_main_close' . $search_index . '_' . $template_name,
         '</main>',
         $search_index
      );
      // End Template Part - Main

      // Close Wrapper
      $wrapper_close = apply_filters(
         'BD616__filter_algolia_template_wrapper_close' . $search_index . '_' . $template_name,
         '</div>',
         $search_index
      );
      $output .= $wrapper_close;

      // Output
      // Filter allows reordering/removal of elements in template
      echo apply_filters(
         'BD616__filter_algolia_template_output_' . $search_index . '_' . $template_name,
         $output,
         $search_index,
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
