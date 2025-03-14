<?php // Modal

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

/**
 * Search Modal Overlay
 */
if (!function_exists('BD616__search_modal_overlay')) :
   function BD616__search_modal_overlay()
   {
      echo '<div id="search_modal_overlay" class="search-modal-overlay"></div>';
   }
endif;
add_action('wp_footer', 'BD616__search_modal_overlay');

/**
 * Search Modal
 *
 * A search box in a modal
 */
if (!function_exists('BD616__search_modal')) :
   function BD616__search_modal()
   { ?>
      <div id="search_modal" class="search-modal">
         <?php if (function_exists('BD616__get_search_form')) {
            echo BD616__get_search_form();
         } ?>
      </div>
<?php }
endif;
add_action('wp_footer', 'BD616__search_modal');
