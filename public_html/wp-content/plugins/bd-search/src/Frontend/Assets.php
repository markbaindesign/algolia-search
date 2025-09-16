<?php

namespace BD324\Search\Frontend;

class Assets
{
    public function register()
    {
        if (!is_admin()) {
            add_action('wp_enqueue_scripts', array($this, 'enqueue'));
            add_action('wp_enqueue_scripts', array($this, 'enqueue_theme'));
            add_action('wp_enqueue_scripts', array($this, 'enqueue_algolia_reset'));
            add_action('wp_enqueue_scripts', array($this, 'enqueue_algolia_theme'));
        }
    }

    public function enqueue()
    {
        $enabled = apply_filters('bd324_search_enable_frontend_style', true);
        if (! $enabled) {
            return;
        }

        $handle  = apply_filters('bd324_search_style_handle', 'bd-search-styles-layout');
        $src     = apply_filters('bd324_search_style_src', BD616__STYLES_URL . '/custom/layout/layout.css');
        $deps    = apply_filters('bd324_search_style_deps', []);
        $version = apply_filters('bd324_search_style_version', BD616__PLUGIN_VERSION);
        $media   = apply_filters('bd324_search_style_media', 'all');

        \wp_enqueue_style($handle, $src, $deps, $version, $media);
    }

    public function enqueue_theme()
    {
        $enabled = apply_filters('bd324_search_enable_frontend_style_theme', true);
        if (! $enabled) {
            return;
        }

        $handle  = apply_filters('bd324_search_theme_style_handle', 'bd-search-styles-theme');
        $src     = apply_filters('bd324_search_theme_style_src', BD616__STYLES_URL . '/custom/theme/theme.css');
        $deps    = apply_filters('bd324_search_theme_style_deps', []);
        $version = apply_filters('bd324_search_theme_style_version', BD616__PLUGIN_VERSION);
        $media   = apply_filters('bd324_search_theme_style_media', 'all');

        \wp_enqueue_style($handle, $src, $deps, $version, $media);
    }

    public function enqueue_algolia_reset()
    {
        $enabled = apply_filters('bd324_search_enable_frontend_style_algolia_reset', true);
        if (! $enabled) {
            return;
        }

        $handle  = apply_filters('bd324_algolia_reset_style_handle', 'algolia-reset');
        $src     = apply_filters('bd324_algolia_reset_style_src', '//cdn.jsdelivr.net/npm/instantsearch.css@7.3.1/themes/reset.css');
        $deps    = apply_filters('bd324_algolia_reset_style_deps', []);
        $version = apply_filters('bd324_algolia_reset_style_version', BD616__PLUGIN_VERSION);
        $media   = apply_filters('bd324_algolia_reset_style_media', 'all');

        \wp_enqueue_style($handle, $src, $deps, $version, $media);
    }

    public function enqueue_algolia_theme()
    {
        $enabled = apply_filters('bd324_search_enable_frontend_style_algolia_theme', true);
        if (! $enabled) {
            return;
        }

        $handle  = apply_filters('bd324_algolia_theme_style_handle', 'algolia-theme');
        $src     = apply_filters('bd324_algolia_theme_style_src', '//cdn.jsdelivr.net/npm/instantsearch.css@7.4.5/themes/satellite.css');
        $deps    = apply_filters('bd324_algolia_theme_style_deps', []);
        $version = apply_filters('bd324_algolia_theme_style_version', BD616__PLUGIN_VERSION);
        $media   = apply_filters('bd324_algolia_theme_style_media', 'all');

        \wp_enqueue_style($handle, $src, $deps, $version, $media);
    }
}
