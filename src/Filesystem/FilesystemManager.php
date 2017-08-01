<?php

namespace WPModular\Filesystem;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use WPModular\Contracts\Filesystem\FilesystemContract;
use WPModular\Filesystem\Adapter\Http;

class FilesystemManager implements FilesystemContract
{
    private $storages = array();

    private function getFilesystemByConfig($name)
    {
        $config = config("filesystem.{$name}");
        if(!array_key_exists('config', $config)) $config['config'] = null;
        return array_values($config);
    }

    private function resolve($type, $path, $config)
    {
        $method = "create{$type}Driver";
        return (method_exists($this, $method)) ? $this->$method($path, $config) : null;
    }

    private function create($name)
    {
        list($type, $path, $config) = $this->getFilesystemByConfig($name);
        return $this->resolve($type, $path, $config);
    }

    public function storage($name)
    {
        if(!array_key_exists($name, $this->storages))
            $this->storages[$name] = $this->create($name);

        return $this->storages[$name];
    }

    /* Each new supported Driver should be added here */
    private function createLocalDriver($path, $config)
    {
        return new Filesystem(new Local($path), $config);
    }

    private function createHttpDriver($path, $config)
    {
        return new Filesystem(new Http($path), $config);
    }
}