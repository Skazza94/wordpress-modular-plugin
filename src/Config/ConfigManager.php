<?php

namespace WPModular\Config;

use Zend\Config\Config;
use WPModular\Contracts\Config\ConfigContract;

class ConfigManager implements ConfigContract
{
    private $path = null;
    private $CONFIG_PATH = 'config';
    private $configs = array();

    public function __construct($path)
    {
        $this->path = $path . DIRECTORY_SEPARATOR . $this->CONFIG_PATH . DIRECTORY_SEPARATOR;
    }

    private function readFile($file)
    {
        $fileName = "{$this->path}{$file}.php";
        return (file_exists($fileName)) ? require_once($fileName) : null;
    }

    private function resolveConfigString($configString)
    {
        $tokens = explode('.', $configString);
        $fileName = array_shift($tokens);
        return array($fileName, $tokens);
    }

    private function loadConfig($file)
    {
        $content = $this->readFile($file);
        return (!is_null($content)) ? new Config($content) : null;
    }

    private function getValue($config, $configPath)
    {
        if(empty($configPath))
            return $config;

        $key = array_shift($configPath);
        return $this->getValue($config[$key], $configPath);
    }

    private function getConfig($name)
    {
        if(!array_key_exists($name, $this->configs))
            return $this->configs[$name] = $this->loadConfig($name);

        return $this->configs[$name];
    }

    public function get($configString)
    {
        list($file, $configPath) = $this->resolveConfigString($configString);
        $config = $this->getConfig($file);
        return (!is_null($config)) ? $this->getValue($config->toArray(), $configPath) : null;
    }

    public function has($configString)
    {
        list($file, $configPath) = $this->resolveConfigString($configString);
        $config = $this->getConfig($file);
        return (!is_null($config)) ? $this->getValue($config->toArray(), $configPath) !== false : false;
    }

    public function all($config)
    {
        list($file, $configPath) = $this->resolveConfigString($config);
        $config = $this->getConfig($file);
        return (!is_null($config)) ? $config->toArray() : array();
    }
}