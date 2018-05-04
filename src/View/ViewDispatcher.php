<?php

namespace WPModular\View;

use WPModular\View\Factories\ViewAdapterFactory;

class ViewDispatcher
{
    private $VIEW_PATH = null;
    private $adapters = array();
    private $supported = null;

    public function __construct($rootPath)
    {
        $configs = config('wp_modular.view');

        $this->supported = $configs['supported_formats'];
        $this->VIEW_PATH = $rootPath . $configs['path'] . DIRECTORY_SEPARATOR;
    }

    public function render($viewName, $params = array(), $print = true, $overrideCache = false)
    {
        $tokens = explode('/', $viewName);
        $fileName = array_pop($tokens);
        $ext = pathinfo($fileName, PATHINFO_EXTENSION);

        if(!in_array($ext, $this->supported))
            return null;

        $path = implode('/', $tokens);

        if(env('USE_CACHE') && !$overrideCache)
            $out = cache()->remember(sha1($viewName), config('wp_modular.cache.expires'), function() use ($ext, $fileName, $path, $params) {
                return $this->renderByType($ext, $fileName, $path, $params);
            });
        else
            $out = $this->renderByType($ext, $fileName, $path, $params);

        if($print)
            echo $out;

        return $out;
    }

    private function renderByType($type, $viewName, $prefix, $params)
    {
        if(!array_key_exists($type, $this->adapters))
            $this->adapters[$type] = $this->create($type);

        return (!is_null($this->adapters[$type])) ? $this->adapters[$type]->render($viewName, $prefix, $params) : null;
    }

    private function create($type)
    {
        return ViewAdapterFactory::getInstance()->create($type, $this->VIEW_PATH);
    }
}
