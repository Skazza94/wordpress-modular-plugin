<?php

namespace WPModular\View;

use WPModular\Foundation\Services\Service;

/**
 * @method string render($viewName, array $params, boolean $print)
 */
class ViewService extends Service
{
    public function bootstrap()
    {
        $this->addMixin(
            new ViewDispatcher($this->app->getRootPath())
        );
    }
}