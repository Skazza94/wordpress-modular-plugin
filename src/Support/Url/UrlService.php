<?php

namespace WPModular\Support\Url;

use WPModular\Foundation\Services\Service;
use WPModular\Support\Url\Adapters\WpUrlAdapter;

class UrlService extends Service
{
    public function bootstrap()
    {
        $this->addMixin(
            new WpUrlAdapter
        );
    }
}