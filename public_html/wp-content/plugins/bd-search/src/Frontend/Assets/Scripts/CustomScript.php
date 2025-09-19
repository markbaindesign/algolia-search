<?php

namespace BD324\Search\Frontend\Assets\Scripts;

class CustomScript extends ScriptBase
{
    use ScriptHelperTrait;

    /**
     * Centralized array of custom scripts with filter keys for extensibility.
     *
     * @var array
     */
    protected $scripts = [
        'search_modal' => [
            'handle'           => 'search-modal',
            'src'              => BD616__SCRIPTS_URL . '/custom/modal/modal.js',
            'deps'             => [],
            'version'          => BD616__PLUGIN_VERSION,
            'in_footer'        => true,
            'enabled_filter'   => 'bd324_search_enable_frontend_script_modal',
            'handle_filter'    => 'bd324_search_script_modal_handle',
            'src_filter'       => 'bd324_search_script_modal_src',
            'deps_filter'      => 'bd324_search_script_modal_deps',
            'version_filter'   => 'bd324_search_script_modal_version',
            'in_footer_filter' => 'bd324_search_script_modal_in_footer',
        ],
        'search_modal_config' => [
            'handle'           => 'search-modal-config',
            'src'              => BD616__SCRIPTS_URL . '/custom/modal/modal-config.js',
            'deps'             => ['search-modal'],
            'version'          => BD616__PLUGIN_VERSION,
            'in_footer'        => true,
            'enabled_filter'   => 'bd324_search_enable_frontend_script_modal_config',
            'handle_filter'    => 'bd324_search_script_modal_config_handle',
            'src_filter'       => 'bd324_search_script_modal_config_src',
            'deps_filter'      => 'bd324_search_script_modal_config_deps',
            'version_filter'   => 'bd324_search_script_modal_config_version',
            'in_footer_filter' => 'bd324_search_script_modal_config_in_footer',
        ],
        'algolia_search_global' => [
            'handle'           => 'algolia-search-global',
            'src'              => BD616__SCRIPTS_URL . '/custom/algolia/global/algolia-search-global.js',
            'deps'             => [],
            'version'          => BD616__PLUGIN_VERSION,
            'in_footer'        => true,
            'enabled_filter'   => 'bd324_search_enable_frontend_script_global',
            'handle_filter'    => 'bd324_search_script_global_handle',
            'src_filter'       => 'bd324_search_script_global_src',
            'deps_filter'      => 'bd324_search_script_global_deps',
            'version_filter'   => 'bd324_search_script_global_version',
            'in_footer_filter' => 'bd324_search_script_global_in_footer',
        ],
        'algolia_search_global_config' => [
            'handle'           => 'algolia-search-global-config',
            'src'              => BD616__SCRIPTS_URL . '/custom/algolia/global/algolia-search-global-config.js',
            'deps'             => ['algolia-search-global'],
            'version'          => BD616__PLUGIN_VERSION,
            'in_footer'        => true,
            'enabled_filter'   => 'bd324_search_enable_frontend_script_global_config',
            'handle_filter'    => 'bd324_search_script_global_config_handle',
            'src_filter'       => 'bd324_search_script_global_config_src',
            'deps_filter'      => 'bd324_search_script_global_config_deps',
            'version_filter'   => 'bd324_search_script_global_config_version',
            'in_footer_filter' => 'bd324_search_script_global_config_in_footer',
        ],
        'algolia_search_global_advanced' => [
            'handle'           => 'algolia-search-global-advanced',
            'src'              => BD616__SCRIPTS_URL . '/custom/algolia/global/advanced/algolia-search-global-advanced.js',
            'deps'             => [],
            'version'          => BD616__PLUGIN_VERSION,
            'in_footer'        => true,
            'enabled_filter'   => 'bd324_search_enable_frontend_script_global_advanced',
            'handle_filter'    => 'bd324_search_script_global_advanced_handle',
            'src_filter'       => 'bd324_search_script_global_advanced_src',
            'deps_filter'      => 'bd324_search_script_global_advanced_deps',
            'version_filter'   => 'bd324_search_script_global_advanced_version',
            'in_footer_filter' => 'bd324_search_script_global_advanced_in_footer',
            'register_only'    => true,
        ],
        'algolia_search_global_advanced_config' => [
            'handle'           => 'algolia-search-global-advanced-config',
            'src'              => BD616__SCRIPTS_URL . '/custom/algolia/global/advanced/algolia-search-global-advanced-config.js',
            'deps'             => ['algolia-search-global-advanced'],
            'version'          => BD616__PLUGIN_VERSION,
            'in_footer'        => true,
            'enabled_filter'   => 'bd324_search_enable_frontend_script_global_advanced_config',
            'handle_filter'    => 'bd324_search_script_global_advanced_config_handle',
            'src_filter'       => 'bd324_search_script_global_advanced_config_src',
            'deps_filter'      => 'bd324_search_script_global_advanced_config_deps',
            'version_filter'   => 'bd324_search_script_global_advanced_config_version',
            'in_footer_filter' => 'bd324_search_script_global_advanced_config_in_footer',
            'register_only'    => true,
        ],
    ];

    /**
     * Registers all custom scripts.
     *
     * @return void
     */
    public function register()
    {
        if (!is_admin()) {
            // Hook registration to plugins_loaded to ensure child plugin filters run first
            add_action('plugins_loaded', [$this, 'register_scripts'], 20);
            add_action('wp_enqueue_scripts', [$this, 'enqueue']);
        }
    }

    /**
     * Register scripts after plugins_loaded
     */
    public function register_scripts()
    {
        // Now child plugins can add/modify scripts via the filter
        $scripts = apply_filters('bd324_register_scripts', $this->scripts);

        if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
            error_log(print_r('BD616__PLUGIN_DEBUG: [PARENT][REG] Custom $scripts keys: ' . implode(', ', array_keys($scripts)) . PHP_EOL, true));
        }
        $this->register_all_scripts($scripts);
    }

    /**
     * Enqueues all custom scripts and localizes translations.
     *
     * @return void
     */
    public function enqueue()
    {
        $scripts = apply_filters('bd324_register_scripts', $this->scripts);

        foreach ($scripts as $key => $script) {
            if (!empty($script['register_only'])) {
                continue;
            }
            $this->enqueue_script($script);
            $this->localize_script_translations($script['handle']);

            // Localize trigger for modal config only
            if ($key === 'search_modal_config') {
                $trigger_modal = apply_filters(
                    'bd324_filter_modal_trigger',
                    '.bd-search-modal-trigger'
                );
                wp_localize_script(
                    'search-modal-config',
                    'trigger_modal',
                    $trigger_modal
                );
            }
        }
    }
}
