<?php
/**
 * Registers an action when plugin is activated.
 *
 * @author Skazza
 */
class ActivationHooker extends PluginHooker {
    /**
     * Abstract method implemented from PluginHooker class.
     * Registers an action when plugin is activated using register_activation_hook function.
     *
     * @param string|array $handler Already parsed handler function.
     * @author Skazza
     */
    protected function callWPRegisterer($handler) {
        register_activation_hook($this->FILE, $handler);
    }
}