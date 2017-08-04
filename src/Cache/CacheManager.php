<?php
namespace WPModular\Cache;

use Symfony\Component\Cache\Simple\FilesystemCache;
use WPModular\Contracts\Cache\CacheContract;

class CacheManager implements CacheContract
{
    private $cache = null;

    public function __construct($rootPath)
    {
        $configs = config('wp_modular.cache');
        $this->cache = new FilesystemCache(
            $configs['namespace'],
            $configs['ttl'],
            $rootPath . DIRECTORY_SEPARATOR . $configs['path']
        );
    }

    public function get($key, $default = null)
    {
        return $this->cache->get($key, $default);
    }

    public function set($key, $value, $ttl = null)
    {
        return $this->cache->set($key, $value, $ttl);
    }

    public function delete($key)
    {
        return $this->cache->delete($key);
    }

    public function clear()
    {
        return $this->cache->clear();
    }

    public function getMultiple($keys, $default = null)
    {
        return $this->cache->getMultiple($keys, $default);
    }

    public function setMultiple($values, $ttl = null)
    {
        $this->cache->setMultiple($values, $ttl);
    }

    public function deleteMultiple($keys)
    {
        return $this->cache->deleteMultiple($keys);
    }

    public function has($key)
    {
        return $this->cache->has($key);
    }

    public function remember($key, $ttl, callable $callback)
    {
        if(!$this->has($key))
            $this->set($key, $callback(), $ttl * 60);

        return $this->get($key);
    }
}