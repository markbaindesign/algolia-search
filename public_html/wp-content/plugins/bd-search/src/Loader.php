<?php

namespace BD324\Search;

use BD324\Search\Admin\Page;
use BD324\Search\Admin\Metaboxes;
use BD324\Search\Frontend\Assets;
use BD324\Search\CLI\IndexCommand;

class Loader
{
    public function register()
    {
        // Admin
        (new Page())->register();
        (new Metaboxes())->register();
        // Frontend
        (new Assets())->register();
        // CLI
        if (defined('WP_CLI') && WP_CLI) {
            // Register the CLI command
            \WP_CLI::add_command('algolia', \BD324\Search\CLI\IndexCommand::class);
        }
    }
}
