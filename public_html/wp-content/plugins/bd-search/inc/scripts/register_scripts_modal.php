<?php

// Register Modal script

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

if(!function_exists('bd324_register_algolia_script_modal')):
   function bd324_register_algolia_script_modal()
   {
      // Enqueue Modal Script
      wp_enqueue_script(
         'search-modal',
         BD616__SCRIPTS_URL . '/custom/modal/modal.js',
         array(),
         BD616__PLUGIN_VERSION,
         array(
            'in_footer' => true,
            'strategy'  => 'defer',
         )
      );

      // Enqueue Modal Script Config
      wp_enqueue_script(
         'search-modal-config',
         BD616__SCRIPTS_URL . '/custom/modal/modal-config.js',
         array('search-modal'),
         BD616__PLUGIN_VERSION,
         array(
            'in_footer' => true,
            'strategy'  => 'defer',
         )
      );

      // Modal trigger class
      $trigger_modal = apply_filters(
         'bd324_filter_modal_trigger',
         '.bd-search-modal-trigger' // default trigger
      );

      wp_localize_script(
         'search-modal-config',
         'trigger_modal',
         $trigger_modal
      );

   }
endif;
add_action('wp_enqueue_scripts', 'bd324_register_algolia_script_modal');
