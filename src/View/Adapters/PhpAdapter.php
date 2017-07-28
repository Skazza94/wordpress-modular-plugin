<?php

namespace WPModular\View\Adapters;

use WPModular\Foundation\View\ViewAdapter;

class PhpAdapter extends ViewAdapter
{
    public function render($viewName, $prefix = '', $params = array(), $print = true)
    {
        if(!empty($params))
            extract($params);

        ob_start();
        include($this->buildViewPath($prefix) . "{$viewName}.php");
        $out = ob_get_contents();
        ob_end_clean();

        if($print)
            echo $print;

        return $out;
    }
}