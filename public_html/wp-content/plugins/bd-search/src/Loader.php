<?php

namespace BD324\Search;

use BD324\Search\Admin\Page;
use BD324\Search\Admin\Metaboxes;

class Loader
{
    public function register()
    {
        // Admin pages
        (new Page())->register();
        (new Metaboxes())->register();

    }
}
