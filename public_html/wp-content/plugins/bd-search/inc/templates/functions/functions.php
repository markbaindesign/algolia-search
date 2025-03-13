<?php // Functions

if (!defined('ABSPATH')) {
   die('Invalid request, dude!');
}

/**
 * Get template for shortcode
 *
 * This is the legacy template function from the shortcode. 
 * 
 * @param $functionParam       functionDescription
 */
if (!function_exists('bd324_get_template_advanced_search')):
   function bd324_get_template_advanced_search($index, $facets)
   {
      /* Vars */
      $output = '';
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

?>
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
<?php
      return $output;
   }
endif;
