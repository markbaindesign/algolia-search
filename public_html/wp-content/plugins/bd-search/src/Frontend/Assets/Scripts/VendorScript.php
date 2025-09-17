<?php

namespace BD324\Search\Frontend\Assets\Scripts;

class VendorScript extends ScriptBase
{
    use ScriptHelperTrait;

    public function register()
    {
        if (!is_admin()) {
            add_action('wp_enqueue_scripts', [$this, 'enqueue']);
        }
    }

    public function enqueue()
    {
        // Algolia main script
        $this->enqueue_script([
            'enabled_filter'   => 'bd324_search_enable_frontend_script_algolia',
            'handle_filter'    => 'bd324_search_script_algolia_handle',
            'src_filter'       => 'bd324_search_script_algolia_src',
            'deps_filter'      => 'bd324_search_script_algolia_deps',
            'version_filter'   => 'bd324_search_script_algolia_version',
            'in_footer_filter' => 'bd324_search_script_algolia_in_footer',
            'handle'           => 'algolia',
            'src'              => 'https://cdn.jsdelivr.net/npm/algoliasearch@4.17.0/dist/algoliasearch-lite.umd.js',
            'deps'             => [],
            'version'          => BD616__PLUGIN_VERSION,
            'in_footer'        => true,
        ]);

        // Instantsearch script
        $this->enqueue_script([
            'enabled_filter'   => 'bd324_search_enable_frontend_script_instantsearch',
            'handle_filter'    => 'bd324_search_script_instantsearch_handle',
            'src_filter'       => 'bd324_search_script_instantsearch_src',
            'deps_filter'      => 'bd324_search_script_instantsearch_deps',
            'version_filter'   => 'bd324_search_script_instantsearch_version',
            'in_footer_filter' => 'bd324_search_script_instantsearch_in_footer',
            'handle'           => 'algolia-instant-search',
            'src'              => 'https://cdn.jsdelivr.net/npm/instantsearch.js@4.55.0/dist/instantsearch.production.min.js',
            'deps'             => ['algolia'],
            'version'          => BD616__PLUGIN_VERSION,
            'in_footer'        => true,
        ]);

        // Algolia client script
        $this->enqueue_script([
            'enabled_filter'   => 'bd324_search_enable_frontend_script_client',
            'handle_filter'    => 'bd324_search_script_client_handle',
            'src_filter'       => 'bd324_search_script_client_src',
            'deps_filter'      => 'bd324_search_script_client_deps',
            'version_filter'   => 'bd324_search_script_client_version',
            'in_footer_filter' => 'bd324_search_script_client_in_footer',
            'handle'           => 'algolia-client',
            'src'              => BD616__SCRIPTS_URL . '/custom/algolia/algolia-search-client.js',
            'deps'             => ['algolia-instant-search'],
            'version'          => BD616__PLUGIN_VERSION,
            'in_footer'        => true,
        ]);

        // Localize Algolia constants
        if (defined('ALGOLIA_APPLICATION_ID') && defined('ALGOLIA_SEARCH_API_KEY')) {
            wp_localize_script(
                'algolia-client',
                'algolia_vars',
                [
                    'app'        => ALGOLIA_APPLICATION_ID,
                    'search_key' => ALGOLIA_SEARCH_API_KEY,
                ]
            );
        }

        // Localize translations for algolia-client
        $this->localize_script_translations('algolia-client');

        // Modal trigger class
        $trigger_modal = apply_filters(
            'bd324_filter_modal_trigger',
            '.bd-search-modal-trigger'
        );
        wp_localize_script(
            'algolia-client',
            'trigger_modal',
            $trigger_modal
        );
    }
}
