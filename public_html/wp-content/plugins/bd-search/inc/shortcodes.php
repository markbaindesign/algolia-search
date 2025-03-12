<?php // Shortcodes

if(!defined('ABSPATH')) {
   die('Invalid request, dude!');
}

if(!function_exists('BD616__search_global')):
   function BD616__search_global()
   {
      $content = '';
      wp_enqueue_script('algolia-search-global');
      $content .= '<div id="searchbox--global" class="searchbox searchbox--global searchbox--dropdown"></div>';
      $content .= '<div id="hits--global" class="hits hits--compact"></div>';
      return $content;
   }
endif;

if(!function_exists('BD616__search_menu')):
   // Menu Search
   function BD616__search_menu()
   {
      $content = '';
      wp_enqueue_script('algolia-search-menu');
      $content .= '<div id="searchbox--menu" class="searchbox searchbox--menu searchbox--global searchbox--dropdown"></div>';
   
      $content .= '<div id="hits--menu" class="hits hits--compact"></div>';
      return $content;
   }
endif;

if(!function_exists('BD616__search_global_echo')):
   function BD616__search_global_echo()
   {
      $content = BD616__search_global();
      echo $content;
   }
endif;

if(!function_exists('BD616__search')):
   // Search shortcode
   function BD616__search()
   {
      ob_start();
      echo BD616__search_global();
      $content = ob_get_clean();
      return $content;
   }
   add_shortcode('search', 'BD616__search');
endif;

if(!function_exists('BD616__searchform_shortcode')):
   /**
    * Search Form Shortcode
    *
    * A search form in a shortcode
    * 
    * @param $att       Shortcode attributes
    */
   function BD616__searchform_shortcode($atts = array())
   {

      // Attributes
      extract(shortcode_atts(array(
         'index' => 'global',
         'template' => ''
      ), $atts));

      // Vars
      $content = '';

      $facets = apply_filters(
         'bd324_filter_search_facets_' . $index,
         array()
      );

      if (isset($atts["index"])) {
         $index = $atts["index"];
      }

      // Enqueue default Algolia Scripts
      algolia_enqueue_default_scripts(); // Vendor

      // Default script handles
      $handle_script = 'algolia-search-' . $index;
      $handle_script_config = 'algolia-search-' . $index . '-config';

      wp_enqueue_script(
         apply_filters(
         'bd324_filter_handle_script_' . $index, 
         $handle_script
         )
      );

      wp_enqueue_script($handle_script_config);

      /* Main Header */
      $layout_header_main = '';
      if (function_exists('bd324_search_form_header')) {
         $layout_header_main = apply_filters(
            'BD616_filter_search_form_header',
            bd324_search_form_header($index)
         );
      }

      /* Sidebar Nav Search */
      $layout_sidebar_search = '';
      if (function_exists('bd324_search_form_header')) {
         $layout_sidebar_search = apply_filters(
            'BD616_filter_search_form_sidebar',
            bd324_search_form_header($index)
         );
      }

      ob_start(); ?>
      <div id="ais-wrapper" class="bd-search search__wrapper search-fullpage search__wrapper--<?php echo $index; ?>">

         <?php echo $layout_header_main; ?>

         <!-- Navigation -->
         <nav class="search__aside">

            <?php echo $layout_sidebar_search; ?>

            <!-- Stats -->
            <div id="search__stats" class="search__stats algolia-stats"></div>

            <?php if (!empty($facets)) : ?>

               <!-- Clear button -->
               <div id="search__clear-refinements" class="search__clear-refinements"></div>

               <!-- Filters / Facets -->
               <div id="toggle-contributor"></div>
               <ul class="search__filters search__filters--<?php echo $index; ?>">
                  <?php foreach ($facets as $facet) : ?>
                     <li class="search__filter search__filters--<?php echo $facet['slug']; ?>">
                        <h6><?php echo $facet['name']; ?></h6>
                        <div class="search__filter ais-facets" id="search__filters--<?php echo $facet['slug']; ?>"></div>
                     </li>
                  <?php endforeach; ?>
               </ul>

            <?php endif; ?>

         </nav>

         <!-- Main -->
         <main id="ais-main" class="search__main">
            <div id="algolia-hits"></div>
            <div id="search__current-refinements" class="search__current-refinements"></div>
            <div id="hits--<?php echo $index; ?>" class="search__results hits hits--<?php echo $index; ?>"></div>
            <div id="algolia-pagination"></div>
         </main>

         <footer class="search__footer">

         </footer>
      </div>

   <?php $content =  ob_get_contents();
      ob_clean();
      return $content;
   }
   add_shortcode('bd-search', 'BD616__searchform_shortcode');
endif;

// Allow shortcodes in widgets
add_filter('widget_text', 'do_shortcode');