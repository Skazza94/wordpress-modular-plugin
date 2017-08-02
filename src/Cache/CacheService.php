<?php
namespace WPModular\Cache;

use WPModular\Foundation\Services\Service;

/**
 * @method string get(string $key, $default = null)
 * @method set(string $key, $value, $ttl = null)
 * @method delete(string $key)
 * @method clear()
 * @method array getMultiple(array $keys, $default = null)
 * @method setMultiple(array $values, $ttl = null)
 * @method deleteMultiple(array $keys)
 * @method has(string $key)
 * @method remember(string $key, $ttl, callable $callback)
 */
class CacheService extends Service
{
    public function bootstrap()
    {
        $this->addMixin(
            $this->app->create(
                CacheManager::class,
                array($this->app->getRootPath())
            )
        );
    }
}