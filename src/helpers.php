<?php

if (!function_exists('app')) {
    /**
     * @param string $key
     * @return mixed
     */
    function app($key = null)
    {
        global $appCtx;

        try {
            return (!is_null($key)) ? $appCtx->get($key) : $appCtx;
        } catch (\Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException $e) {
            return null;
        }
    }
}

if (!function_exists('cache')) {
    /**
     * @return \WPModular\Cache\CacheService
     */
    function cache()
    {
        return app('cache');
    }
}

if (!function_exists('config')) {
    /**
     * @return mixed
     */
    function config($configString)
    {
        return app('config')->get($configString);
    }
}

if (!function_exists('add_cron_interval')) {
    function add_cron_interval($name, $minutes)
    {
        app()->singleton(\WPModular\Cron\CronManager::class)->addCronInterval($name, $minutes);
    }
}

if (!function_exists('env')) {
    /**
     * @return string
     */
    function env($key)
    {
        return app('env')->get($key);
    }
}

if (!function_exists('storage')) {
    /**
     * @return \League\Flysystem\Filesystem
     */
    function storage($name)
    {
        return app('filesystem')->storage($name);
    }
}

if (!function_exists('localize')) {
    /**
     * @return string
     */
    function localize($tag)
    {
        return app('l10n')->translate($tag);
    }
}

if (!function_exists('url')) {
    /**
     * @return \WPModular\Support\Url\UrlService
     */
    function url()
    {
        return app('url');
    }
}

if (!function_exists('render')) {
    /**
     * @return string
     */
    function render($viewName, $params = [], $print = true, $overrideCache = false)
    {
        return app('view')->render($viewName, $params, $print, $overrideCache);
    }
}
