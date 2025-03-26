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
 * @param $index       The index to search
 * @param $template_name       The template name
 */
if (!function_exists('bd324_show_advanced_search_template')):
   function bd324_show_advanced_search_template($index, $template_name = '')
   {
      $output = '';

      // Enqueue default Algolia scripts
      if (function_exists('algolia_enqueue_default_scripts')) {
         algolia_enqueue_default_scripts();
      }

      // Enqueue Index Scripts
      $handle_script = bd324_get_script_handles($index, $template_name);
      if (wp_script_is($handle_script, 'registered')) {
         wp_enqueue_script($handle_script);
      } else {
         error_log(print_r($handle_script . ' is not registered! Cannot enqueue!', true));
      }

      $handle_script_config = bd324_get_script_handles($index, $template_name, true);
      if (wp_script_is($handle_script_config, 'registered')) {
         wp_enqueue_script($handle_script_config);
      } else {
         error_log(print_r($handle_script_config . ' is not registered! Cannot enqueue!', true));
      }

      // Open Wrapper
      $wrapper_open = apply_filters(
         'BD616__filter_algolia_listings_wrapper_open',
         '<div id="ais-wrapper" class="bd-search search__wrapper search-wrapper search-fullpage search-wrapper--listings search-wrapper--' . $index . '">',
         $index
      );
      $output .= $wrapper_open;

      // Start Template Part - Header
      $output .= apply_filters(
         'BD616__filter_algolia_search_template_advanced_header',
         '<header id="ais-header" class="search__header">',
         $index
      );

      // Search
      $search = bd324_get_algolia_template_part('searchbox', $index, $template_name);
      $output .= $search;

      $output .= apply_filters(
         'BD616__filter_algolia_search_template_advanced_header_close',
         '</header>',
         $index
      );
      // End Template Part - Aside

      // Start Template Part - Aside
      $output .= apply_filters(
         'BD616__filter_algolia_search_template_advanced_aside',
         '<nav id="ais-aside" class="search__aside">',
         $index
      );

      // Stats
      $stats = bd324_get_algolia_template_part('stats', $index, $template_name);
      $output .= $stats;

      // Clear Filters
      $filters_clear = bd324_get_algolia_template_part('filters_clear', $index, $template_name);
      $output .= $filters_clear;

      // Filters
      $filters = bd324_get_algolia_template_part('filters', $index, $template_name);
      $output .= $filters;

      $output .= apply_filters(
         'BD616__filter_algolia_search_template_advanced_aside_close',
         '</nav>',
         $index
      );
      // End Template Part - Aside

      // Start Template Part - Main
      $output .= apply_filters(
         'BD616__filter_algolia_search_template_advanced_main',
         '<main id="ais-main" class="search__main">',
         $index
      );

      // Current Filters
      $filters_current = bd324_get_algolia_template_part('filters_current', $index, $template_name);
      $output .= $filters_current;

      // Hits
      $hits = bd324_get_algolia_template_part('hits', $index, $template_name);
      $output .= $hits;

      $output .= apply_filters(
         'BD616__filter_algolia_search_template_advanced_main_close',
         '</main>',
         $index
      );
      // End Template Part - Main

      // Close Wrapper
      $wrapper_close = apply_filters(
         'BD616__filter_algolia_listings_wrapper_close',
         '</nav>',
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
