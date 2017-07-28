<?php

namespace WPModular\Foundation\Services;

use WPModular\Contracts\ApplicationContext\ApplicationContextContract;
use WPModular\Contracts\Services\ServiceContract;
use WPModular\Foundation\Proxies\Traits\Proxable;

abstract class Service implements ServiceContract
{
    use Proxable;

    protected $app = null;

    public function __construct(ApplicationContextContract $app)
    {
        $this->app = $app;
        $this->bootstrap();
    }
}