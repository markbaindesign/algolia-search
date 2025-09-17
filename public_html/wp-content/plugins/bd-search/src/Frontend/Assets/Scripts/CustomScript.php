<?php

namespace BD324\Search\Frontend\Assets\Scripts;

class CustomScript extends ScriptBase
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
        // Modal script
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

        // Modal config script
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

        // Localize trigger for modal config
        $trigger_modal = apply_filters(
            'bd324_filter_modal_trigger',
            '.bd-search-modal-trigger'
        );
        wp_localize_script(
            'search-modal-config',
            'trigger_modal',
            $trigger_modal
        );

        // Algolia global script
        $this->enqueue_script([
            'enabled_filter'   => 'bd324_search_enable_frontend_script_algolia_global',
            'handle_filter'    => 'bd324_search_script_algolia_global_handle',
            'src_filter'       => 'bd324_search_script_algolia_global_src',
            'deps_filter'      => 'bd324_search_script_algolia_global_deps',
            'version_filter'   => 'bd324_search_script_algolia_global_version',
            'in_footer_filter' => 'bd324_search_script_algolia_global_in_footer',
            'handle'           => 'algolia-search-global',
            'src'              => BD616__SCRIPTS_URL . '/custom/algolia/global/algolia-search-global.js',
            'deps'             => [],
            'version'          => BD616__PLUGIN_VERSION,
            'in_footer'        => true,
        ]);

        $this->enqueue_script([
            'enabled_filter'   => 'bd324_search_enable_frontend_script_algolia_global_config',
            'handle_filter'    => 'bd324_search_script_algolia_global_config_handle',
            'src_filter'       => 'bd324_search_script_algolia_global_config_src',
            'deps_filter'      => 'bd324_search_script_algolia_global_config_deps',
            'version_filter'   => 'bd324_search_script_algolia_global_config_version',
            'in_footer_filter' => 'bd324_search_script_algolia_global_config_in_footer',
            'handle'           => 'algolia-search-global-config',
            'src'              => BD616__SCRIPTS_URL . '/custom/algolia/global/algolia-search-global-config.js',
            'deps'             => ['algolia-search-global'],
            'version'          => BD616__PLUGIN_VERSION,
            'in_footer'        => true,
        ]);

        // Algolia global advanced script
        $this->enqueue_script([
            'enabled_filter'   => 'bd324_search_enable_frontend_script_algolia_global_advanced',
            'handle_filter'    => 'bd324_search_script_algolia_global_advanced_handle',
            'src_filter'       => 'bd324_search_script_algolia_global_advanced_src',
            'deps_filter'      => 'bd324_search_script_algolia_global_advanced_deps',
            'version_filter'   => 'bd324_search_script_algolia_global_advanced_version',
            'in_footer_filter' => 'bd324_search_script_algolia_global_advanced_in_footer',
            'handle'           => 'algolia-search-global-advanced',
            'src'              => BD616__SCRIPTS_URL . '/custom/algolia/global/advanced/algolia-search-global-advanced.js',
            'deps'             => [],
            'version'          => BD616__PLUGIN_VERSION,
            'in_footer'        => true,
        ]);

        $this->enqueue_script([
            'enabled_filter'   => 'bd324_search_enable_frontend_script_algolia_global_advanced_config',
            'handle_filter'    => 'bd324_search_script_algolia_global_advanced_config_handle',
            'src_filter'       => 'bd324_search_script_algolia_global_advanced_config_src',
            'deps_filter'      => 'bd324_search_script_algolia_global_advanced_config_deps',
            'version_filter'   => 'bd324_search_script_algolia_global_advanced_config_version',
            'in_footer_filter' => 'bd324_search_script_algolia_global_advanced_config_in_footer',
            'handle'           => 'algolia-search-global-advanced-config',
            'src'              => BD616__SCRIPTS_URL . '/custom/algolia/global/advanced/algolia-search-global-advanced-config.js',
            'deps'             => ['algolia-search-global-advanced'],
            'version'          => BD616__PLUGIN_VERSION,
            'in_footer'        => true,
        ]);
    }
}
