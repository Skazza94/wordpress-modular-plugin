<?php
/**
 * Second level of abstraction from concrete classes, this Hooker subclass
 * defines a type of hookers that register actions when plugin is activated/deactivated.
 *
 * @author Skazza
 */

namespace WPModular\Foundation\Hooker\Wordpress\PluginHooks;

use WPModular\Foundation\Hooker\Hook;

abstract class PluginHook extends Hook
{
    /**
     * @var string Main file of the plugin, used to register/unregister actions.
     */
    protected $FILE = null;

    /**
     * Basic constructor to init the FILE constant.
     *
     * @author Skazza
     */
    public function __construct()
    {
        $this->FILE = app()->getRootPath() . DIRECTORY_SEPARATOR . config('wp_modular.plugin_slug') . '.php';
    }

    /**
     * Abstract method implemented from Hooker class.
     * It registers the action into WordPress core using the callWPRegisterer method.
     *
     * @param Object $data Not used here.
     * @param string|array $handler Already parsed handler function.
     * @return boolean If everything as been hooked or not.
     * @author Skazza
     */
    protected function hookSpecific($data, $handler)
    {
        $this->callWPRegisterer($handler); /* Call the real WordPress function to register this action */
        return true;
    }

    /**
     * Abstract method, implemented into PluginHooker subclasses.
     * This method receives the handler and calls the WordPress function to register the action
     * depending on the hook type.
     *
     * @param string|array $handler Already parsed handler function.
     * @author Skazza
     */
    protected abstract function callWPRegisterer($handler);
}