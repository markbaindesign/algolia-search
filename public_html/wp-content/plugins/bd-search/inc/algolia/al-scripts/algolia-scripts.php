<?php

// Algolia Scripts

if (!defined('ABSPATH')) {
    die('Invalid request, dude.');
}

/**
 * BD324 Debug helper function for consistent logging
 */
function bd324_debug_log($message, $data = null)
{
    if (defined('BD324_DEBUG') && BD324_DEBUG) {
        $debug_message = '[BD324 debug] ' . $message;
        if ($data !== null) {
            $debug_message .= ' | Data: ' . print_r($data, true);
        }
        error_log($debug_message);
    }
}

function algolia_load_assets()
{
    bd324_debug_log('Starting algolia_load_assets function');

    // Get current language
    $active_language = array(
       'active_language' => apply_filters('wpml_current_language', null),
    );

    bd324_debug_log('Active language retrieved', $active_language);

    // Translations Array
    $algolia_translations = array(
       'placeholder_search' => esc_attr__(
           apply_filters(
               'bd324_filter_search_input_placeholder',
               __('Search', '_bd_algolia_search_plugin')
           )
       ),
       'label_reset' => esc_attr__('Clear', '_bd_algolia_search_plugin'),
       'label_empty' => esc_attr__('Nothing found', '_bd_algolia_search_plugin'),
       'label_more' => esc_attr__('More', '_bd_algolia_search_plugin'),
    );

    bd324_debug_log('Algolia translations prepared', $algolia_translations);

    // Enqueue Algolia
    wp_enqueue_script(
        'algolia',
        'https://cdn.jsdelivr.net/npm/algoliasearch@4.17.0/dist/algoliasearch-lite.umd.js',
        array(),
        BD616__PLUGIN_VERSION,
        true
    );

    bd324_debug_log('Algolia core script enqueued');

    // Enqueue Instantsearch
    wp_enqueue_script(
        'algolia-instant-search',
        'https://cdn.jsdelivr.net/npm/instantsearch.js@4.55.0/dist/instantsearch.production.min.js',
        array('algolia'),
        BD616__PLUGIN_VERSION,
        array(
          'in_footer' => true,
          'strategy'  => 'defer',
      )
    );

    bd324_debug_log('Algolia InstantSearch script enqueued');

    // Enqueue Client
    wp_enqueue_script(
        'algolia-client',
        BD616__SCRIPTS_URL . '/custom/algolia/algolia-search-client.js',
        array('algolia-instant-search'),
        BD616__PLUGIN_VERSION,
        array(
          'in_footer' => true,
          'strategy'  => 'defer',
      )
    );

    bd324_debug_log('Algolia client script enqueued');

    // Localize script
    $algolia_constants = array(
       'app'          => ALGOLIA_APPLICATION_ID,
       'search_key'   => ALGOLIA_SEARCH_API_KEY,
       'debug'        => defined('BD324_DEBUG') ? BD324_DEBUG : false
    );

    bd324_debug_log('Algolia constants prepared for localization', $algolia_constants);

    wp_localize_script(
        'algolia-client',
        'algolia_vars',
        $algolia_constants
    );

    bd324_debug_log('Algolia constants localized to algolia_vars');

    wp_localize_script(
        'algolia-search-global', // handle
        'algolia_translations_object', //
        $algolia_translations
    );

    bd324_debug_log('Algolia translations localized to algolia_translations_object');

    // Localize Team script to add
    // active language
    wp_localize_script(
        'algolia-team-search', // handle
        'algolia_active_lang_object', //
        $active_language
    );

    bd324_debug_log('Active language localized to algolia_active_lang_object', $active_language);
    bd324_debug_log('algolia_load_assets function completed successfully');
}
add_action('wp_enqueue_scripts', 'algolia_load_assets');

function algolia_enqueue_default_scripts()
{
    wp_enqueue_script('algolia');
    wp_enqueue_script('algolia-instant-search');
    wp_enqueue_script('algolia-client');
}
