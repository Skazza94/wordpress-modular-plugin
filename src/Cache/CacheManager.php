<?php

namespace WPModular\Cache;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use WPModular\Contracts\Cache\CacheContract;

class CacheManager implements CacheContract
{
    private $cache = null;

    public function __construct($rootPath)
    {
        $configs = config('wp_modular.cache');
        $this->cache = new FilesystemAdapter(
            $configs['namespace'],
            $configs['ttl'],
            $rootPath . DIRECTORY_SEPARATOR . $configs['path']
        );
    }

    public function delete($key)
    {
        return $this->cache->delete($key);
    }

    public function clear()
    {
        return $this->cache->clear();
    }

    public function remember($key, $ttl, callable $callback)
    {
        if (!$this->has($key))
            $this->set($key, $callback(), $ttl * 60);

        return $this->get($key);
    }

    public function has($key)
    {
        $item = $this->cache->getItem($key);
        return $item->isHit();
    }

    public function set($key, $value, $ttl = null)
    {
        $item = $this->cache->getItem($key);
        $item->set($value);
        $item->expiresAfter($ttl);

        $this->cache->save($item);

        return $value;
    }

    public function get($key, $default = null)
    {
        $item = $this->cache->getItem($key);
        return $item->isHit() ? $item->get() : $default;
    }
}