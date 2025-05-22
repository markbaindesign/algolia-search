<?php // Form

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

/**
 * Search Form
 *
 * Returns search form
 * @param   $index         Algolia index ID
 * @return  $form          Search form
 */
if (!function_exists('BD616__get_search_form')) :
   function BD616__get_search_form($index = 'global')
   {
      $form = '';

      // Scripts
      if(function_exists('algolia_enqueue_default_scripts')):
         algolia_enqueue_default_scripts();
      endif;
      $script = 'algolia-search-' . $index;
      $config_script = 'algolia-search-' . $index . '-config';

      wp_enqueue_script($script);
      wp_enqueue_script($config_script);

      $modal_header = apply_filters(
         'bd324_filter_modal_header',
         __('Search', '_bd_algolia_search_plugin')
      );

      ob_start();
?>

      <!-- Header -->
      <header class="search-modal__header search__header">

         <!-- Title -->
         <h2 class="search-modal__title"><?php echo $modal_header; ?></h2>

         <!-- Close -->
         <div id="search__wrapper__close" class="search-modal__close-button" title="<?php echo __('Close Search', '_bd_algolia_search_plugin') ?>"><i></i><span class="visually-hidden"><?php echo __('Close', '_bd_algolia_search_plugin') ?></span></div>

         <!-- Input -->
         <div class="algolia-search__wrapper search-wrapper search-wrapper--<?php echo $index; ?>">
            <div id="algolia-search-box--<?php echo $index; ?>" class="searchbox searchbox--<?php echo $index; ?> algolia-search-box">
            </div>
         </div>

         <!-- Filters -->
         <div class="search-modal__filters">
            <ul class="search__filters">
               <li class="search__filter ais-facets" id="search__filters--post_type"></li>
            </ul>
            <div id="search__clear-refinements" class="search__clear-refinements"></div>
         </div>
      </header>

      <!-- Main -->
      <main id="ais-main" class="search__main search-modal__main">

         <div id="algolia-hits"></div>
         <div id="search__current-refinements" class="search__current-refinements"></div>
         <div id="hits--<?php echo $index; ?>" class="search__results hits hits--<?php echo $index; ?>"></div>
         <div id="algolia-pagination"></div>
      </main>

      <!-- Modal Footer -->
      <?php echo bd324_get_modal_footer(); ?>

<?php
      $form = ob_get_clean();

      return $form;
   }
endif;

/**
 * Search Form Header
 * 
 * @param $index       Algolia index name
 */
if (!function_exists('bd324_search_form_header')):
   function bd324_search_form_header($index)
   {
      $output = '';
      $output .= '<header class="search__header">';
      $output .= '<div class="algolia-search__wrapper search-wrapper search-wrapper--' . $index . '">';
      $output .= '<div id="algolia-search-box--' . $index . '" class="searchbox searchbox--' . $index . ' algolia-search-box">';
      $output .= '</div>';
      $output .= '</div>';
      $output .= '</header>';
      return $output;
   }
endif;
