<?php

namespace WPModular\Contracts\Cache;

use Psr\SimpleCache\CacheInterface;

interface CacheContract extends CacheInterface
{
    public function remember($key, $ttl, callable $callback);
}