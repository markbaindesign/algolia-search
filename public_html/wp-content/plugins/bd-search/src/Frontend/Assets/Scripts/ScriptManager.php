<?php

namespace BD324\Search\Frontend\Assets\Scripts;

class ScriptManager
{
    /**
     * Array of script handler objects.
     *
     * @var array
     */
    protected $script_handlers = [];

    public function __construct()
    {
        // Add your script handler classes here, in desired order
        $this->script_handlers[] = new VendorScript();
        $this->script_handlers[] = new CustomScript();
        // Add more handlers if needed
    }

    /**
     * Register all scripts via their handlers.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->script_handlers as $handler) {
            $handler->register();
        }
    }

    /**
     * Enqueue all scripts via their handlers.
     *
     * @return void
     */
    public function enqueue()
    {
        foreach ($this->script_handlers as $handler) {
            $handler->enqueue();
        }
    }
}
