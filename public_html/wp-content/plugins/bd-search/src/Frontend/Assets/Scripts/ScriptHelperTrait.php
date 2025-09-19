<?php

namespace BD324\Search\Frontend\Assets\Scripts;

trait ScriptHelperTrait
{
    /**
     * Enqueues a WordPress script with customizable arguments and filters.
     *
     * @param array $args
     * @return void
     */
    protected function enqueue_script($args)
    {
        $enabled = apply_filters($args['enabled_filter'] ?? '', true);
        if (!$enabled) {
            return;
        }

        $handle    = apply_filters($args['handle_filter'] ?? '', $args['handle']);
        $src       = apply_filters($args['src_filter'] ?? '', $args['src']);
        $deps      = apply_filters($args['deps_filter'] ?? '', $args['deps'] ?? []);
        $version   = apply_filters($args['version_filter'] ?? '', $args['version'] ?? BD616__PLUGIN_VERSION);
        $in_footer = apply_filters($args['in_footer_filter'] ?? '', $args['in_footer'] ?? true);

        \wp_enqueue_script($handle, $src, $deps, $version, $in_footer);

        // Debug: Check if the script is enqueued
        if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
            if (wp_scripts()->queue && in_array($handle, wp_scripts()->queue)) {
                error_log(print_r('BD616__PLUGIN_DEBUG: ' . $handle . ' is enqueued (wp_scripts).' . PHP_EOL, true));
            }
        }
    }

    /**
     * Registers a WordPress script with customizable arguments and filters.
     *
     * @param array $args
     * @return void
     */
    protected function register_script($args)
    {
        add_action('wp_enqueue_scripts', function () use ($args) {
            $enabled = apply_filters($args['enabled_filter'] ?? '', true);
            if (!$enabled) {
                return;
            }

            $handle    = apply_filters($args['handle_filter'] ?? '', $args['handle']);
            $src       = apply_filters($args['src_filter'] ?? '', $args['src']);
            $deps      = apply_filters($args['deps_filter'] ?? '', $args['deps'] ?? []);
            $version   = apply_filters($args['version_filter'] ?? '', $args['version'] ?? BD616__PLUGIN_VERSION);
            $in_footer = apply_filters($args['in_footer_filter'] ?? '', $args['in_footer'] ?? true);

            \wp_register_script($handle, $src, $deps, $version, $in_footer);

            // Debug: Check if the script is registered
            if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
                $registered_scripts = wp_scripts()->registered;
                $is_registered = $registered_scripts[$handle] ?? null;
                if ($is_registered) {
                    error_log(print_r('BD616__PLUGIN_DEBUG: ' . $handle . ' is registered (wp_scripts).' . PHP_EOL, true));
                }
            }
        });
    }

    /**
     * Localizes translations and active language for a script using ArrayHelpers.
     *
     * @param string $handle The script handle to localize.
     * @return void
     */
    protected function localize_script_translations($handle)
    {
        $script_handle_underscores = str_replace('-', '_', $handle);

        // Use ArrayHelpers for translations
        $translations = \BD324\Search\Helpers\ArrayHelpers::get_algolia_translation($script_handle_underscores);

        wp_localize_script(
            $handle,
            'translations_object_' . $script_handle_underscores,
            $translations
        );

        // Localize active language
        $active_language = [
            'active_language' => apply_filters('wpml_current_language', null),
        ];
        wp_localize_script(
            $handle,
            'algolia_active_lang_object',
            $active_language
        );
    }

    /**
     * Registers all scripts used in the theme or plugin.
     *
     * @return void
     */
    protected function register_all_scripts($scripts)
    {
        // Register each script
        foreach ($scripts as $key => $args) {
            $this->register_script($args);
        }
    }
}
