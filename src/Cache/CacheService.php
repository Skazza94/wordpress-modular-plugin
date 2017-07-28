<?php
namespace WPModular\Cache;

use WPModular\Foundation\Services\Service;

class CacheService extends Service
{
    public function bootstrap()
    {
        $this->addMixin(new CacheManager($this->app->getRootPath()));
    }
}