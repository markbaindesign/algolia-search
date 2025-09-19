<?php

namespace BD324\Search\Frontend\Assets\Scripts;

class VendorScript extends ScriptBase
{
    use ScriptHelperTrait;

    /**
     * Centralized array of vendor scripts.
     *
     * @var array
     */
    protected $scripts = [];

    public function __construct()
    {
        add_filter('bd324_register_scripts_vendor', function ($scripts) {
            $scripts['algolia'] = [
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
            ];
            $scripts['algolia_instant_search'] = [
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
            ];
            $scripts['algolia_client'] = [
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
            ];

            return $scripts;
        });
    }

    public function register()
    {
        if (!is_admin()) {
            $scripts = apply_filters('bd324_register_scripts_vendor', $this->scripts);

            if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
                error_log(print_r('BD616__PLUGIN_DEBUG: [VENDOR] Scripts keys: ' . implode(', ', array_keys($scripts)) . PHP_EOL, true));
            }
            $this->register_all_scripts($scripts);
            add_action('wp_enqueue_scripts', [$this, 'enqueue']);
        }
    }

    public function enqueue()
    {
        $scripts = apply_filters('bd324_register_scripts_vendor', $this->scripts);
        foreach ($scripts as $key => $script) {
            if (!empty($script['register_only'])) {
                continue;
            }
            $this->enqueue_script($script);
            $this->localize_script_translations($script['handle']);
            if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
                error_log(print_r('BD616__PLUGIN_DEBUG: Enqueued script: ' . ($script['handle'] ?? 'unknown') . PHP_EOL, true));
            }


            // Special handling for algolia-client

            if ($key !== 'algolia_client') {
                continue;
            }

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

            // Modal trigger class
            $trigger_modal = apply_filters(
                'bd324_filter_modal_trigger',
                '.bd-search-modal-trigger'
            );
            wp_localize_script(
                'algolia-client',
                'trigger_modal',
                [$trigger_modal]
            );
        }
    }
}
