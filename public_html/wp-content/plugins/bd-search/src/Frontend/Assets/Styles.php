<?php

/**
 * Handles registration and enqueuing of frontend CSS styles for the BD324 Search plugin.
 *
 * allows customization via filters.
 *
 * @package BD324\Search\Frontend
 */

namespace BD324\Search\Frontend\Assets;

class Styles
{
    public function register()
    {
        if (!is_admin()) {
            add_action('wp_enqueue_scripts', array($this, 'enqueue_all_styles'));
        }
    }

    public function enqueue_all_styles()
    {
        $this->enqueue_algolia_reset();
        $this->enqueue_algolia_theme();
        $this->enqueue_base();
        $this->enqueue_theme();
    }

    /**
     * Enqueues a WordPress style with customizable arguments and filters.
     *
     * This method applies a series of filters to the provided arguments, allowing
     * for dynamic modification of the style's handle, source, dependencies, version,
     * and media type before enqueuing the style using WordPress's wp_enqueue_style().
     *
     * @param array $args {
     *     Array of arguments for enqueuing the style.
     *
     *     @type string   $enabled_filter  Filter name to determine if the style should be enqueued.
     *     @type string   $handle_filter   Filter name for the style handle.
     *     @type string   $src_filter      Filter name for the style source URL.
     *     @type string   $deps_filter     Filter name for the style dependencies.
     *     @type string   $version_filter  Filter name for the style version.
     *     @type string   $media_filter    Filter name for the media type.
     *     @type string   $handle          Default handle for the style.
     *     @type string   $src             Default source URL for the style.
     * }
     *
     * @return void
     */
    protected function enqueue_style($args)
    {
        $enabled = apply_filters($args['enabled_filter'], true);
        if (! $enabled) {
            return;
        }

        $handle  = apply_filters($args['handle_filter'], $args['handle']);
        $src     = apply_filters($args['src_filter'], $args['src']);
        $deps    = apply_filters($args['deps_filter'], []);
        $version = apply_filters($args['version_filter'], BD616__PLUGIN_VERSION);
        $media   = apply_filters($args['media_filter'], 'all');

        \wp_enqueue_style($handle, $src, $deps, $version, $media);
    }

    /**
     * Enqueues the base layout stylesheet for the BD Search plugin.
     *
     * This method calls enqueue_style() with a set of filters and parameters that allow
     * customization of the base layout stylesheet's handle, source URL, dependencies, version, and media type.
     * The default stylesheet is located at BD616__STYLES_URL . '/custom/layout/layout.css'.
     *
     * Filters available for customization:
     * - 'bd324_search_enable_frontend_style': Enable or disable the base layout style.
     * - 'bd324_search_style_handle': Modify the stylesheet handle.
     * - 'bd324_search_style_src': Change the stylesheet source URL.
     * - 'bd324_search_style_deps': Set stylesheet dependencies.
     * - 'bd324_search_style_version': Specify the stylesheet version.
     * - 'bd324_search_style_media': Set the media attribute for the stylesheet.
     *
     * @return void
     */
    public function enqueue_base()
    {
        $this->enqueue_style([
            'enabled_filter' => 'bd324_search_enable_frontend_style',
            'handle_filter'  => 'bd324_search_style_handle',
            'src_filter'     => 'bd324_search_style_src',
            'deps_filter'    => 'bd324_search_style_deps',
            'version_filter' => 'bd324_search_style_version',
            'media_filter'   => 'bd324_search_style_media',
            'handle'         => 'bd-search-styles-layout',
            'src'            => BD616__STYLES_URL . '/custom/layout/layout.css',
        ]);
    }

    /**
     * Enqueues the theme-specific frontend stylesheet for the BD Search plugin.
     *
     * This method calls enqueue_style() with a set of filters and parameters that allow
     * customization of the theme stylesheet's handle, source URL, dependencies, version, and media type.
     * The default stylesheet is located at BD616__STYLES_URL . '/custom/theme/theme.css'.
     *
     * Filters available for customization:
     * - 'bd324_search_enable_frontend_style_theme': Enable or disable the theme style.
     * - 'bd324_search_theme_style_handle': Modify the stylesheet handle.
     * - 'bd324_search_theme_style_src': Change the stylesheet source URL.
     * - 'bd324_search_theme_style_deps': Set stylesheet dependencies.
     * - 'bd324_search_theme_style_version': Specify the stylesheet version.
     * - 'bd324_search_theme_style_media': Set the media attribute for the stylesheet.
     *
     * @return void
     */
    public function enqueue_theme()
    {
        $this->enqueue_style([
            'enabled_filter' => 'bd324_search_enable_frontend_style_theme',
            'handle_filter'  => 'bd324_search_theme_style_handle',
            'src_filter'     => 'bd324_search_theme_style_src',
            'deps_filter'    => 'bd324_search_theme_style_deps',
            'version_filter' => 'bd324_search_theme_style_version',
            'media_filter'   => 'bd324_search_theme_style_media',
            'handle'         => 'bd-search-styles-theme',
            'src'            => BD616__STYLES_URL . '/custom/theme/theme.css',
        ]);
    }

    /**
     * Enqueues the Algolia InstantSearch reset CSS stylesheet for the frontend.
     *
     * This method uses the `enqueue_style` function to add the Algolia reset stylesheet,
     * allowing customization via WordPress filters for enabling, handle, source, dependencies,
     * version, and media type.
     *
     * Filters used:
     * - bd324_search_enable_frontend_style_algolia_reset: Enable or disable the stylesheet.
     * - bd324_algolia_reset_style_handle: Customize the stylesheet handle.
     * - bd324_algolia_reset_style_src: Customize the stylesheet source URL.
     * - bd324_algolia_reset_style_deps: Customize the stylesheet dependencies.
     * - bd324_algolia_reset_style_version: Customize the stylesheet version.
     * - bd324_algolia_reset_style_media: Customize the stylesheet media type.
     *
     * @return void
     */
    public function enqueue_algolia_reset()
    {
        $this->enqueue_style([
            'enabled_filter' => 'bd324_search_enable_frontend_style_algolia_reset',
            'handle_filter'  => 'bd324_algolia_reset_style_handle',
            'src_filter'     => 'bd324_algolia_reset_style_src',
            'deps_filter'    => 'bd324_algolia_reset_style_deps',
            'version_filter' => 'bd324_algolia_reset_style_version',
            'media_filter'   => 'bd324_algolia_reset_style_media',
            'handle'         => 'algolia-reset',
            'src'            => '//cdn.jsdelivr.net/npm/instantsearch.css@7.3.1/themes/reset.css',
        ]);
    }

    /**
     * Enqueues the Algolia theme stylesheet for the frontend search interface.
     *
     * This method uses the `enqueue_style` helper to register and enqueue the Algolia theme CSS
     * from a CDN. It allows customization of the style's properties via WordPress filters:
     * - 'bd324_search_enable_frontend_style_algolia_theme': Enable or disable the stylesheet.
     * - 'bd324_algolia_theme_style_handle': Customize the style handle.
     * - 'bd324_algolia_theme_style_src': Customize the stylesheet source URL.
     * - 'bd324_algolia_theme_style_deps': Specify dependencies for the stylesheet.
     * - 'bd324_algolia_theme_style_version': Set the stylesheet version.
     * - 'bd324_algolia_theme_style_media': Set the media attribute for the stylesheet.
     *
     * @return void
     */

    public function enqueue_algolia_theme()
    {
        $this->enqueue_style([
            'enabled_filter' => 'bd324_search_enable_frontend_style_algolia_theme',
            'handle_filter'  => 'bd324_algolia_theme_style_handle',
            'src_filter'     => 'bd324_algolia_theme_style_src',
            'deps_filter'    => 'bd324_algolia_theme_style_deps',
            'version_filter' => 'bd324_algolia_theme_style_version',
            'media_filter'   => 'bd324_algolia_theme_style_media',
            'handle'         => 'algolia-theme',
            'src'            => '//cdn.jsdelivr.net/npm/instantsearch.css@7.4.5/themes/satellite.css',
        ]);
    }
}
