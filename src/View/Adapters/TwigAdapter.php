<?php

namespace WPModular\View\Adapters;

use \Timber\Timber;
use WPModular\Foundation\View\ViewAdapter;

class TwigAdapter extends ViewAdapter
{
    public function __construct($viewPath)
    {
        parent::__construct($viewPath);
        Timber::$locations = $this->VIEW_PATH;
    }

    public function render($viewName, $prefix = '', $params = array(), $print = true)
    {
        $context = array_merge(Timber::get_context(), $params, array('wp_service' => wp_service(), 'url' => url()));
        $out = Timber::fetch($this->buildPrefix($prefix) . $viewName, $context);
        if($print)
            echo $out;

        return $out;
    }
}