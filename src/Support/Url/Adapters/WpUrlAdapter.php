<?php

namespace WPModular\Support\Url\Adapters;

use WPModular\Foundation\Support\UrlAdapter;

class WpUrlAdapter extends UrlAdapter
{
    public function parseUrl($url, $component = -1)
    {
        return wp_parse_url($url, $component);
    }

    public function getUrlFor($id)
    {
        return (is_numeric($id)) ? get_permalink(abs($id)) : home_url($id);
    }
}