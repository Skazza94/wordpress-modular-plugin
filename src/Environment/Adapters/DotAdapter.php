<?php

namespace WPModular\Environment\Adapters;

use Dotenv\Dotenv;
use WPModular\Contracts\Environment\EnvironmentContract;

class DotAdapter implements EnvironmentContract
{
    private $path = null;

    public function __construct($path)
    {
        $this->path = $path;
        $this->load();
    }

    public function load()
    {
        $dotenv = new Dotenv($this->path);
        $dotenv->load();
    }

    public function get($key)
    {
        return env_get($key);
    }

    public function set($key, $value)
    {
        putenv("{$key}={$value}");
    }
}

if (!function_exists('env_get')) {
    function env_get($key, $default = null)
    {
        $value = getenv($key);

        if ($value === false)
            return $default;

        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return null;
        }

        if (substr($value, 0) === '"' && substr($value, -1) === '"')
            return substr($value, 1, -1);

        return $value;
    }
}