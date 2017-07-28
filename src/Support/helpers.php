<?php

use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

if(!function_exists('app')) {
    function app($key = null)
    {
        global $appCtx;

        try {
            return (!is_null($key)) ? $appCtx->getService($key) : $appCtx;
        } catch (ServiceNotFoundException $e) {
            return null;
        }
    }
}

if(!function_exists('view')) {
    /**
     * @return \WPModular\View\ViewService
     */
    function view()
    {
        return app('view');
    }
}

if(!function_exists('url')) {
    /**
     * @return \WPModular\Support\Url\UrlService
     */
    function url()
    {
        return app('url');
    }
}

if(!function_exists('wp_service')) {
    /**
     * @return \WPModular\Wordpress\WordpressService
     */
    function wp_service()
    {
        return app('wp');
    }
}