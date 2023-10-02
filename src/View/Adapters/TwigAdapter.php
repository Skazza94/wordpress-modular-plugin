<?php

namespace WPModular\View\Adapters;

use Timber\Timber;
use WPModular\Foundation\View\ViewAdapter;

class TwigAdapter extends ViewAdapter
{
    public function __construct($viewPath)
    {
        parent::__construct($viewPath);
        Timber::$locations = $this->VIEW_PATH;
    }

    public function render($viewName, $prefix = '', $params = [])
    {
        $context = array_merge(
            Timber::get_context(),
            $params,
            [
                'cache' => cache(),
                'env' => app('env'),
                'l10n' => app('l10n'),
                'wp_url' => url()
            ]
        );
        return Timber::fetch($this->buildPrefix($prefix) . $viewName, $context);
    }
}