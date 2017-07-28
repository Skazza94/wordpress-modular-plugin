<?php

namespace WPModular\View;

use WPModular\Foundation\Services\Service;

class ViewService extends Service
{
    public function bootstrap()
    {
        $this->addMixin(new ViewDispatcher($this->app->getRootPath()));
    }
}