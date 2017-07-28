<?php

namespace WPModular\Config;

use WPModular\Foundation\Services\Service;

class ConfigService extends Service
{
    public function bootstrap()
    {
        $this->addMixin(new ConfigManager($this->app->getRootPath()));
    }
}