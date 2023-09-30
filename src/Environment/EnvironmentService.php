<?php

namespace WPModular\Environment;

use WPModular\Environment\Adapters\DotAdapter;
use WPModular\Foundation\Services\Service;

/**
 * @method string get(string $key)
 */
class EnvironmentService extends Service
{
    public function bootstrap()
    {
        $this->addMixin(
            $this->app->create(
                DotAdapter::class,
                ['path' => $this->app->getRootPath()]
            )
        );
    }
}