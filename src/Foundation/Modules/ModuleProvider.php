<?php

namespace WPModular\Foundation\Modules;

use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;
use WPModular\Contracts\ApplicationContext\ApplicationContextContract;
use WPModular\Hooker\Hooker;

abstract class ModuleProvider
{
    protected $app = null;

    public function __construct(ApplicationContextContract $app)
    {
        $this->app = $app;
    }

    public function boot()
    {
        $namespace = $this->usesNamespace();
        $folder = new Filesystem(
            new LocalFilesystemAdapter(
                $this->usesFolder()
            )
        );

        $this->register();

        $this->app->create(
            Hooker::class,
            ['namespace' => $namespace, 'filesystem' => $folder]
        )->hookModules();
    }

    abstract public function usesNamespace();

    abstract public function usesFolder();

    abstract public function register();
}