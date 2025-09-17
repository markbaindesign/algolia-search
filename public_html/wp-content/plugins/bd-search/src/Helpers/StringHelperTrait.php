<?php

namespace BD324\Search\Helpers;

trait StringHelperTrait
{
    protected function get_lang_suffix($algolia_index_language)
    {
        return $algolia_index_language ? '_' . $algolia_index_language : '';
    }

    protected function prefix_index_name($name)
    {
        global $table_prefix;
        return $table_prefix . $name;
    }
}
