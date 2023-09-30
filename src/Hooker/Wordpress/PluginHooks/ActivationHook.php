<?php
/**
 * Registers an action when plugin is activated.
 *
 * @author Skazza
 */

namespace WPModular\Hooker\Wordpress\PluginHooks;

use WPModular\Foundation\Hooker\Wordpress\PluginHooks\PluginHook;

class ActivationHook extends PluginHook
{
    /**
     * Abstract method implemented from PluginHooker class.
     * Registers an action when plugin is activated using register_activation_hook function.
     *
     * @param string|array $handler Already parsed handler function.
     * @author Skazza
     */
    protected function callWPRegisterer($handler)
    {
        register_activation_hook($this->FILE, $handler);
    }
}