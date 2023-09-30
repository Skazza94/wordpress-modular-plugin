<?php

namespace WPModular\Foundation\Support;

use WPModular\Contracts\Support\UrlContract;

abstract class UrlAdapter implements UrlContract
{
    public function buildUrl($parts, $additionalQueryParams = [], $additionalPathParams = [])
    {
        if (!is_array($parts))
            $parts = $this->parseUrl($parts);

        $parts['query'] = $this->addQueryParameters($parts, $additionalQueryParams);
        $parts['path'] = $this->addPathParameters($parts, $additionalPathParams);

        return (isset($parts['host']) ? (
                (isset($parts['scheme']) ? "$parts[scheme]://" : '//') .
                (isset($parts['user']) ? $parts['user'] . (isset($parts['pass']) ? ":$parts[pass]" : '') . '@' : '') .
                $parts['host'] .
                (isset($parts['port']) ? ":$parts[port]" : '')
            ) : '') .
            ((isset($parts['path']) && !is_null($parts['path'])) ? $parts['path'] : '/') .
            ((isset($parts['query']) && !is_null($parts['query'])) ? '?' . (is_array($parts['query']) ? http_build_query($parts['query'], '', '&') : $parts['query']) : '') .
            (isset($parts['fragment']) ? "#$parts[fragment]" : '');
    }

    private function addQueryParameters($parts, $additional)
    {
        $query = [];
        if (isset($parts['query'])) {
            if (!is_array($parts['query']))
                parse_str($parts['query'], $query);
            else
                $query = $parts['query'];
        }

        $query = array_merge($query, $additional);
        return (empty($query)) ? null : $query;
    }

    private function addPathParameters($parts, $additional)
    {
        $path = [];
        if (isset($parts['path']))
            $path = explode('/', $parts['path']);

        $path = array_merge($path, $additional);
        $path = $this->encodePathTokens($path);
        return (empty($path)) ? null : preg_replace('#/+#', '/', $path);
    }

    private function encodePathTokens($path)
    {
        return implode('/', array_map('rawurlencode', array_map('rawurldecode', $path)));
    }
}