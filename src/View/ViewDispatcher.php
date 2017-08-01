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

    public function render($viewName, $params = array(), $print = true)
    {
        $tokens = explode('/', $viewName);
        $viewName = array_pop($tokens);
        $ext = pathinfo($viewName, PATHINFO_EXTENSION);

        if(!in_array($ext, $this->supported))
            return null;

        return $this->renderByType($ext, $viewName, implode('/', $tokens), $params, $print);
    }

    private function renderByType($type, $viewName, $prefix, $params, $print)
    {
        if(!array_key_exists($type, $this->adapters))
            $this->adapters[$type] = $this->create($type);

        return (!is_null($this->adapters[$type])) ? $this->adapters[$type]->render($viewName, $prefix, $params, $print) : null;
    }

    private function create($type)
    {
        return ViewAdapterFactory::getInstance()->create($type, $this->VIEW_PATH);
    }
}