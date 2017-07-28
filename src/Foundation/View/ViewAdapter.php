<?php

namespace WPModular\Foundation\View;

use WPModular\Contracts\View\ViewContract;

abstract class ViewAdapter implements ViewContract
{
    protected $VIEW_PATH = null;

    public function __construct($viewPath)
    {
        $this->VIEW_PATH = $viewPath;
    }

    protected function buildViewPath($prefix)
    {
        return preg_replace('#' . DIRECTORY_SEPARATOR . '+#', DIRECTORY_SEPARATOR, ($this->VIEW_PATH . $this->buildPrefix($prefix))); /* Removes unnecessary slashes */
    }

    protected function buildPrefix($prefix)
    {
        return (substr($prefix, -1) !== DIRECTORY_SEPARATOR) ? $prefix . DIRECTORY_SEPARATOR : $prefix;
    }
}