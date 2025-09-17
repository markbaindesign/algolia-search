<?php

namespace BD324\Search\Frontend\Assets;

/**
 * Handles registration and enqueuing of frontend JS scripts for the BD324 Search plugin.
 * Allows customization via filters.
 *
 * @package BD324\Search\Frontend
 */
class Scripts
{
    /**
     * Registers the script enqueue actions for the frontend.
     *
     * @return void
     */
    public function register()
    {
        if (!is_admin()) {
            add_action('wp_enqueue_scripts', array($this, 'enqueue_all_scripts'));
        }
    }

    /**
     * Enqueues all required frontend scripts for the plugin.
     *
     * @return void
     */
    public function enqueue_all_scripts()
    {
        $this->enqueue_default_scripts();
        $this->enqueue_modal();
        $this->enqueue_modal_config();
    }

    /**
     * Enqueues a WordPress script with customizable arguments and filters.
     *
     * @param array $args {
     *     @type string   $enabled_filter  Filter name to determine if the script should be enqueued.
     *     @type string   $handle_filter   Filter name for the script handle.
     *     @type string   $src_filter      Filter name for the script source URL.
     *     @type string   $deps_filter     Filter name for the script dependencies.
     *     @type string   $version_filter  Filter name for the script version.
     *     @type string   $in_footer_filter Filter name for in_footer param.
     *     @type string   $handle          Default handle for the script.
     *     @type string   $src             Default source URL for the script.
     *     @type array    $deps            Default dependencies.
     *     @type string   $version         Default version.
     *     @type bool     $in_footer       Default in_footer value.
     * }
     *
     * @return void
     */
    protected function enqueue_script($args)
    {
        $enabled = apply_filters($args['enabled_filter'], true);
        if (! $enabled) {
            return;
        }

        $handle    = apply_filters($args['handle_filter'], $args['handle']);
        $src       = apply_filters($args['src_filter'], $args['src']);
        $deps      = apply_filters($args['deps_filter'], isset($args['deps']) ? $args['deps'] : []);
        $version   = apply_filters($args['version_filter'], isset($args['version']) ? $args['version'] : BD616__PLUGIN_VERSION);
        $in_footer = apply_filters($args['in_footer_filter'], isset($args['in_footer']) ? $args['in_footer'] : true);

        \wp_enqueue_script($handle, $src, $deps, $version, $in_footer);
    }

    /**
     * Enqueues the default Algolia scripts (algolia, instantsearch, client).
     *
     * @return void
     */
    public function enqueue_default_scripts()
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

        // Localize translations
        $algolia_translations = [
            'placeholder_search' => esc_attr__(
                apply_filters(
                    'bd324_filter_search_input_placeholder',
                    __('Search', '_bd_algolia_search_plugin')
                )
            ),
            'label_reset' => esc_attr__('Clear', '_bd_algolia_search_plugin'),
            'label_empty' => esc_attr__('Nothing found', '_bd_algolia_search_plugin'),
            'label_more'  => esc_attr__('More', '_bd_algolia_search_plugin'),
        ];
        wp_localize_script(
            'algolia-client',
            'algolia_translations_object',
            $algolia_translations
        );

        // Localize active language
        $active_language = [
            'active_language' => apply_filters('wpml_current_language', null),
        ];
        wp_localize_script(
            'algolia-client',
            'algolia_active_lang_object',
            $active_language
        );
    }

    /**
     * Enqueues the modal JavaScript for the search modal functionality.
     *
     * @return void
     */
    public function enqueue_modal()
    {
        $this->enqueue_script([
            'enabled_filter'   => 'bd324_search_enable_frontend_script_modal',
            'handle_filter'    => 'bd324_search_script_modal_handle',
            'src_filter'       => 'bd324_search_script_modal_src',
            'deps_filter'      => 'bd324_search_script_modal_deps',
            'version_filter'   => 'bd324_search_script_modal_version',
            'in_footer_filter' => 'bd324_search_script_modal_in_footer',
            'handle'           => 'search-modal',
            'src'              => BD616__SCRIPTS_URL . '/custom/modal/modal.js',
            'deps'             => [],
            'version'          => BD616__PLUGIN_VERSION,
            'in_footer'        => true,
        ]);
    }

    /**
     * Enqueues the modal config JavaScript and localizes the trigger selector.
     *
     * @return void
     */
    public function enqueue_modal_config()
    {
        $this->enqueue_script([
            'enabled_filter'   => 'bd324_search_enable_frontend_script_modal_config',
            'handle_filter'    => 'bd324_search_script_modal_config_handle',
            'src_filter'       => 'bd324_search_script_modal_config_src',
            'deps_filter'      => 'bd324_search_script_modal_config_deps',
            'version_filter'   => 'bd324_search_script_modal_config_version',
            'in_footer_filter' => 'bd324_search_script_modal_config_in_footer',
            'handle'           => 'search-modal-config',
            'src'              => BD616__SCRIPTS_URL . '/custom/modal/modal-config.js',
            'deps'             => ['search-modal'],
            'version'          => BD616__PLUGIN_VERSION,
            'in_footer'        => true,
        ]);

        // Modal trigger class
        $trigger_modal = apply_filters(
            'bd324_filter_modal_trigger',
            '.bd-search-modal-trigger' // default trigger
        );

        // Localize the trigger to the config script
        wp_localize_script(
            'search-modal-config',
            'trigger_modal',
            $trigger_modal
        );
    }
}
