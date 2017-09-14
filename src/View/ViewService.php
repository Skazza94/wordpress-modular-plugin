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
            $this->app->create(
                ViewDispatcher::class,
                $this->app->getRootPath()
            )
        );
    }
}