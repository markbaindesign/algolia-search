<?php // 

if(!defined('ABSPATH')) {
   die('Invalid request, dude!');
}
/**
 * Get Algolia Translations
 *
 * Get the Algolia translations
 * @param $functionParam       functionDescription
 */
if(!function_exists('bd324_get_algolia_translation')):
   function bd324_get_algolia_translation($script_handle)
   {
      $translations = array();

      //  Searchbox Placeholder
      $translations['placeholder_search'] = esc_attr__(
         apply_filters(
         'bd324_filter_search_input_placeholder_' . $script_handle,
         __('Search', '_bd_algolia_search_plugin')
         )
      );

      $translations['label_reset'] = esc_attr__(
         apply_filters(
         'bd324_filter_label_reset_' . $script_handle,
         __('Clear', '_bd_algolia_search_plugin')
         )
      );

      $translations['label_empty'] = esc_attr__(
         apply_filters(
         'bd324_filter_label_empty_' . $script_handle,
         __('Nothing found', '_bd_algolia_search_plugin')
         )
      );

      $translations['label_more'] = esc_attr__(
         apply_filters(
         'bd324_filter_label_more_' . $script_handle,
         __('More', '_bd_algolia_search_plugin')
         )
      );

      $translations['label_reset_filters'] = esc_attr__(
         apply_filters(
         'bd324_filter_label_reset_filters_' . $script_handle,
         __('Reset filters', '_bd_algolia_search_plugin')
         )
      );

      $translations['label_no_filters'] = esc_attr__(
         apply_filters(
         'bd324_filter_label_no_filters_' . $script_handle,
         __('No filters', '_bd_algolia_search_plugin')
         )
      );

      return apply_filters('bd324_filter_algolia_translations_' . $script_handle, $translations);
   }
endif;
