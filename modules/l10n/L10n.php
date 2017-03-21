<?php
/**
 * Registers the language domain of this plugin.
 *
 * @author Skazza
 */
class L10n {
    /**
     * Registers the language domain of this plugin.
     *
     * @author Skazza
     */
    public function loadPluginLanguageDomain() {
        load_plugin_textdomain(PLUGIN_NAME, false, PLUGIN_SLUG . '/languages');
    }
}