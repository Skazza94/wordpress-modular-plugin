<?php

namespace WPModular\Config;

use Laminas\Config\Factory;
use WPModular\Contracts\Config\ConfigContract;

class ConfigManager implements ConfigContract
{
    private $path = null;
    private $CONFIG_PATH = 'config';
    private $configs = [];

    public function __construct($path)
    {
        $this->path = $path . DIRECTORY_SEPARATOR . $this->CONFIG_PATH . DIRECTORY_SEPARATOR;
    }

    public function get($configString)
    {
        list($file, $configPath) = $this->resolveConfigString($configString);

        if (empty($configPath))
            return $this->all($configString);

        $config = $this->getConfig($file);
        return (!is_null($config)) ? $this->getValue($config->toArray(), $configPath) : null;
    }

    private function resolveConfigString($configString)
    {
        $tokens = explode('.', $configString);
        $fileName = array_shift($tokens);
        return [$fileName, $tokens];
    }

    public function all($config)
    {
        list($file, $configPath) = $this->resolveConfigString($config);
        $config = $this->getConfig($file);
        return (!is_null($config)) ? $config->toArray() : [];
    }

    private function getConfig($name)
    {
        if (!array_key_exists($name, $this->configs))
            return $this->configs[$name] = $this->loadConfig($name);

        return $this->configs[$name];
    }

    private function loadConfig($file)
    {
        $path = $this->getFilePath($file);
        return (!is_null($path)) ? Factory::fromFile($path, true) : null;
    }

    private function getFilePath($file)
    {
        $fileName = "{$this->path}{$file}.php";
        return (file_exists($fileName)) ? $fileName : null;
    }

    private function getValue($config, $configPath)
    {
        if (empty($configPath))
            return $config;

        $key = array_shift($configPath);
        return $this->getValue($config[$key], $configPath);
    }

    public function has($configString)
    {
        list($file, $configPath) = $this->resolveConfigString($configString);
        $config = $this->getConfig($file);
        return (!is_null($config)) ? $this->getValue($config->toArray(), $configPath) !== false : false;
    }
}