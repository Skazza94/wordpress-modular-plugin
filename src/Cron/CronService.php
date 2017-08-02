<?php

namespace WPModular\Cron;

use WPModular\Foundation\Services\Service;

/**
 * @method registerCronEvent(string $tag, string $interval)
 */
class CronService extends Service
{
    public function bootstrap()
    {
        $file = $this->app->getRootPath() . DIRECTORY_SEPARATOR . env('PLUGIN_SLUG') . '.php';

        register_activation_hook($file, array($this, 'registerEvents'));
        register_deactivation_hook($file, array($this, 'unregisterEvents'));

        $this->addMixin(
            $this->app->create(
                CronManager::class
            )
        );
    }
}