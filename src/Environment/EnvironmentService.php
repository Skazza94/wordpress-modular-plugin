<?php

namespace WPModular\Environment;

use WPModular\Environment\Adapters\DotAdapter;
use WPModular\Foundation\Services\Service;

class EnvironmentService extends Service
{
    public function bootstrap()
    {
        $this->addMixin(
            new DotAdapter($this->app->getRootPath())
        );
    }
}