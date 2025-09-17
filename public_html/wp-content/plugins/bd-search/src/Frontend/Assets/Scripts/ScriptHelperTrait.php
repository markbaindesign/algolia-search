<?php

namespace BD324\Search\Frontend\Assets\Scripts;

trait ScriptHelperTrait
{
    protected function enqueue_script($args)
    {
        $enabled = apply_filters($args['enabled_filter'], true);
        if (!$enabled) {
            return;
        }

        $handle    = apply_filters($args['handle_filter'], $args['handle']);
        $src       = apply_filters($args['src_filter'], $args['src']);
        $deps      = apply_filters($args['deps_filter'], $args['deps'] ?? []);
        $version   = apply_filters($args['version_filter'], $args['version'] ?? BD616__PLUGIN_VERSION);
        $in_footer = apply_filters($args['in_footer_filter'], $args['in_footer'] ?? true);

        \wp_enqueue_script($handle, $src, $deps, $version, $in_footer);
    }

    /**
     * Localizes translations for a script using bd324_get_algolia_translation.
     *
     * @param string $handle The script handle to localize.
     * @return void
     */
    protected function localize_script_translations($handle)
    {
        $script_handle_underscores = str_replace('-', '_', $handle);
        wp_localize_script(
            $handle,
            'translations_object_' . $script_handle_underscores,
            bd324_get_algolia_translation($handle)
        );
    }
    /**
     * Localizes the active language for a given script handle.
     *
     * @param string $handle The script handle to localize.
     * @return void
     */
    protected function localize_active_language($handle)
    {
        $active_language = [
            'active_language' => apply_filters('wpml_current_language', null),
        ];
        wp_localize_script(
            $handle,
            'algolia_active_lang_object',
            $active_language
        );
    }
}
