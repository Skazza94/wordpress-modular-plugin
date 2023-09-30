<?php

namespace WPModular\Filesystem;

use League\Flysystem\Local\LocalFilesystemAdapter;
use League\Flysystem\Filesystem;
use WPModular\Contracts\Filesystem\FilesystemContract;
use WPModular\Filesystem\Adapter\HttpFilesystemAdapter;

class FilesystemManager implements FilesystemContract
{
    private $storages = [];

    public function storage($name)
    {
        if (!array_key_exists($name, $this->storages))
            $this->storages[$name] = $this->create($name);

        return $this->storages[$name];
    }

    private function create($name)
    {
        list($type, $path, $config) = $this->getFilesystemByConfig($name);
        return $this->resolve($type, $path, $config);
    }

    private function getFilesystemByConfig($name)
    {
        $config = config("filesystem.{$name}");
        if (!array_key_exists('config', $config)) $config['config'] = null;
        return array_values($config);
    }

    private function resolve($type, $path, $config)
    {
        $method = "create{$type}Driver";
        return (method_exists($this, $method)) ? $this->$method($path, $config) : null;
    }

    /* Each new supported Driver should be added here */

    private function createLocalDriver($path, $config)
    {
        return new Filesystem(new LocalFilesystemAdapter($path), $config);
    }

    private function createHttpDriver($path, $config)
    {
        return new Filesystem(new HttpFilesystemAdapter($path), $config);
    }
}