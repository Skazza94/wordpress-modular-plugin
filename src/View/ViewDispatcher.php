<?php

namespace WPModular\View;

use WPModular\View\Factories\ViewAdapterFactory;
use WPModular\Contracts\View\ViewContract as ViewContract;

class ViewDispatcher implements ViewContract
{
    private $VIEW_PATH = null;
    private $adapters = array();
    private $supported = null;

    public function __construct($rootPath)
    {
        $configs = app('config')->get('wp_modular.view');

        $this->supported = $configs['supported_formats'];
        $this->VIEW_PATH = $rootPath . DIRECTORY_SEPARATOR . $configs['path'] . DIRECTORY_SEPARATOR;
    }

    public function render($viewName, $prefix = '', $params = array(), $print = true)
    {
        $ext = pathinfo($viewName, PATHINFO_EXTENSION);

        if(!in_array($ext, $this->supported))
            return null;

        return $this->renderByType($ext, $viewName, $prefix, $params, $print);
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