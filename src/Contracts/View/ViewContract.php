<?php

namespace WPModular\Contracts\View;

interface ViewContract
{
    public function render($viewName, $prefix = '', $params = array());
}