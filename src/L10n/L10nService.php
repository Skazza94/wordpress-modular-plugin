<?php

namespace WPModular\L10n;

use WPModular\Foundation\Services\Service;

/**
 * @method string translate(string $tag)
 */
class L10nService extends Service
{
    public function bootstrap()
    {
        add_action('plugins_loaded', array($this, 'loadPluginLanguageDomain'));

        $this->addMixin(
            $this->app->create(
                TranslationManager::class,
                array(env('PLUGIN_NAME'))
            )
        );
    }

    public function loadPluginLanguageDomain()
    {
        load_plugin_textdomain(
            env('PLUGIN_NAME'),
            false,
            wp_service()->getPluginName() . DIRECTORY_SEPARATOR . 'languages'
        );
    }
}