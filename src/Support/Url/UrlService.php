<?php

namespace WPModular\Support\Url;

use WPModular\Foundation\Services\Service;
use WPModular\Support\Url\Adapters\WpUrlAdapter;

/**
 * @method string buildUrl($parts, array $additionalQueryParams, array $additionalPathParams)
 * @method mixed parseUrl(string $url, integer $component)
 * @method string getUrlFor(mixed $id)
 */
class UrlService extends Service
{
    public function bootstrap()
    {
        $this->addMixin(new WpUrlAdapter);
    }
}