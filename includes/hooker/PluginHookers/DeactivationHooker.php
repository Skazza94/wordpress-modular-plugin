<?php
/**
 * Unregisters an action when plugin is deactivated.
 *
 * @author Skazza
 */
class DeactivationHooker extends PluginHooker {
    /**
     * Abstract method implemented from PluginHooker class.
     * Unregisters an action when plugin is deactivated using register_deactivation_hook function.
     *
     * @param string|array $handler Already parsed handler function.
     * @author Skazza
     */
    protected function callWPRegisterer($handler) {
        register_deactivation_hook($this->FILE, $handler);
    }
}