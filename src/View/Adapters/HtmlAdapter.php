<?php

namespace WPModular\View\Adapters;

use WPModular\Foundation\View\ViewAdapter;

class HtmlAdapter extends ViewAdapter
{
    public function render($viewName, $prefix = '', $params = array())
    {
        return file_get_contents($this->buildViewPath($prefix) . "{$viewName}.html");
    }
}