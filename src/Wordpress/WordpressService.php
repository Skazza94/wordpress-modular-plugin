<?php

namespace WPModular\Wordpress;

use WPModular\Foundation\Services\Service;

class WordpressService extends Service
{
    public function bootstrap()
    {
        parent::bootstrap();
    }

    public function getPluginName()
    {
        return env('PLUGIN_SLUG');
    }

    public function getPluginVersion()
    {
        return env('VERSION');
    }
}