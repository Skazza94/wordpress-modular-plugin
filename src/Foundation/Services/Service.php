<?php

namespace WPModular\Foundation\Services;

use WPModular\Contracts\ApplicationContext\ApplicationContextContract;
use WPModular\Contracts\Services\ServiceContract;
use WPModular\Foundation\Macros\Macroable;

abstract class Service implements ServiceContract
{
    use Macroable;

    protected $app = null;

    public function __construct(ApplicationContextContract $app)
    {
        $this->app = $app;
        $this->bootstrap();
    }

    public function bootstrap()
    {
        if($this->app->isLoaded())
            return;
    }
}