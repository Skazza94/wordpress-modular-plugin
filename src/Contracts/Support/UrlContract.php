<?php

namespace WPModular\Contracts\Support;

interface UrlContract
{
    public function parseUrl($url, $component = -1);
    public function buildUrl($parts, $additionalQueryParams = array(), $additionalPathParams = array());
    public function getUrlFor($id);
}