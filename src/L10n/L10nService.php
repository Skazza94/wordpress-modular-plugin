<?php

namespace WPModular\L10n;

use WPModular\Foundation\Services\Service;

/**
 * @method string translate(string $tag)
 */
class L10nService extends Service
{
    static public $BOOTLOAD = true;

    public function bootstrap()
    {
        add_action('plugins_loaded', [$this, 'loadPluginLanguageDomain']);

        $this->addMixin(
            $this->app->create(
                TranslationManager::class,
                ['textDomain' => config('wp_modular.plugin_name')]
            )
        );
    }

    public function loadPluginLanguageDomain()
    {
        load_plugin_textdomain(
            config('wp_modular.plugin_name'),
            false,
            config('wp_modular.plugin_slug') . DIRECTORY_SEPARATOR . config('wp_modular.l10n.path')
        );
    }
}