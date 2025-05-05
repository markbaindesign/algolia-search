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
 * @param $index_name       The index to search
 * @param $template_name       The template name
 */
if (!function_exists('bd324_show_search_template')):
   function bd324_show_search_template($index_name, $template_name = '')
   {
      $output = '';

      // Enqueue default Algolia scripts
      if (function_exists('algolia_enqueue_default_scripts')) {
         algolia_enqueue_default_scripts();
      }

      // Enqueue Index Scripts
      $handle_script = bd324_get_script_handles($index_name, $template_name);
      bd324_enqueue_script_if_registered($handle_script);

      $handle_script_config = bd324_get_script_handles($index_name, $template_name, true);
      bd324_enqueue_script_if_registered($handle_script_config);

      // Open Wrapper
      $wrapper_open = apply_filters(
         'BD616__filter_algolia_template_wrapper_' . $index_name . '_' . $template_name,
         '<div id="ais-wrapper" class="bd-search search__wrapper search-wrapper search-fullpage search-wrapper--listings search-wrapper--' . $index_name . ' search-wrapper--' . $template_name . '">',
         $index_name
      );
      $output .= $wrapper_open;

      // Start Template Part - Header
      $output .= apply_filters(
         'BD616__filter_algolia_template_header_' . $index_name . '_' . $template_name,
         '<header id="ais-header" class="search__header">',
         $index_name
      );

      // Search
      $search = bd324_get_algolia_template_part('searchbox', $index_name, $template_name);
      $output .= $search;

      $output .= apply_filters(
         'BD616__filter_algolia_template_header_close_' . $index_name . '_' . $template_name,
         '</header>',
         $index_name
      );
      // End Template Part - Aside

      // Start Template Part - Aside
      $aside = '';
      $aside .= apply_filters(
         'BD616__filter_algolia_template_aside_open_' . $index_name . '_' . $template_name,
         '<nav id="ais-aside" class="search__aside">',
         $index_name
      );

      // Stats
      $stats = bd324_get_algolia_template_part('stats', $index_name, $template_name);
      $aside .= $stats;

      // Clear Filters
      $filters_clear = bd324_get_algolia_template_part('filters_clear', $index_name, $template_name);
      $aside .= $filters_clear;

      // Filters
      $filters = bd324_get_algolia_template_part('filters', $index_name, $template_name);
      $aside .= $filters;

      $aside .= apply_filters(
         'BD616__filter_algolia_template_aside_close_' . $index_name . '_' . $template_name,
         '</nav>',
         $index_name
      );
      $output .= apply_filters(
         'BD616__filter_algolia_template_aside_' . $index_name . '_' . $template_name,
         $aside,
         $index_name
      );
      // End Template Part - Aside

      // Start Template Part - Main
      $output .= apply_filters(
         'BD616__filter_algolia_template_main_' . $index_name . '_' . $template_name,
         '<main id="ais-main" class="search__main">',
         $index_name
      );

      // Current Filters
      $filters_current = bd324_get_algolia_template_part('filters_current', $index_name, $template_name);
      $output .= $filters_current;

      // Hits
      $hits = bd324_get_algolia_template_part('hits', $index_name, $template_name);
      $output .= $hits;

      $output .= apply_filters(
         'BD616__filter_algolia_template_main_close_' . $index_name . '_' . $template_name,
         '</main>',
         $index_name
      );
      // End Template Part - Main

      // Close Wrapper
      $wrapper_close = apply_filters(
         'BD616__filter_algolia_template_wrapper_close_' . $index_name . '_' . $template_name,
         '</div>',
         $index_name
      );
      $output .= $wrapper_close;

      // Output
      // Filter allows reordering/removal of elements in template
      echo apply_filters(
         'BD616__filter_algolia_template_output_' . $index_name . '_' . $template_name,
         $output,
         $index_name,
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
