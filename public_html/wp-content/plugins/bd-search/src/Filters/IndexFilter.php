<?php

namespace BD324\Search\Filters;

use BD324\Search\Helpers\StringHelperTrait;

class IndexFilter
{
    use StringHelperTrait;

    public function __construct()
    {
        add_filter('bd324_filter_index_name', [$this, 'filter_index_name'], 10, 1);
    }

    public function filter_index_name($name, $algolia_index_language = '')
    {
        $name = $this->prefix_index_name($name);
        $name .= $this->get_lang_suffix($algolia_index_language);
        return $name;
    }
}
