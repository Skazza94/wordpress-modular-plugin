<?php

namespace WPModular\Config;

use WPModular\Foundation\Services\Service;

/**
 * @method mixed get(string $configString)
 * @method boolean has(string $configString)
 * @method array all(string $configString)
 */
class ConfigService extends Service
{
    public function bootstrap()
    {
        $this->addMixin(
            $this->app->create(
                ConfigManager::class,
                $this->app->getRootPath()
            )
        );
    }
}