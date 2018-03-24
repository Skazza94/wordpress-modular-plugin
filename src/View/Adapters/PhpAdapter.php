<?php

namespace WPModular\View\Adapters;

use WPModular\Foundation\View\ViewAdapter;

class PhpAdapter extends ViewAdapter
{
    public function render($viewName, $prefix = '', $params = array())
    {
        if(!empty($params))
            extract($params);

        ob_start();
        include($this->buildViewPath($prefix) . $viewName);
        $out = ob_get_contents();
        ob_end_clean();

        return $out;
    }
}
