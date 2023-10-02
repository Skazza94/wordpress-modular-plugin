<?php

namespace WPModular\Contracts\Cache;

interface CacheContract
{
    public function delete($key);

    public function clear();

    public function remember($key, $ttl, callable $callback);

    public function has($key);

    public function set($key, $value, $ttl = null);

    public function get($key, $default = null);
}