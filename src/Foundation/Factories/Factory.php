<?php

namespace WPModular\Foundation\Factories;

use Gnugat\NomoSpaco\File\FileRepository;
use Gnugat\NomoSpaco\FqcnRepository;
use Gnugat\NomoSpaco\Token\ParserFactory;
use WPModular\Foundation\Support\Singleton;

abstract class Factory extends Singleton
{
    protected $fqnsService = null;
    protected $searchPath = null;

    protected function __construct()
    {
        $fileRepository = app()->create(FileRepository::class);
        $parserFactory = app()->create(ParserFactory::class);

        $this->fqnsService = app()->singleton(
            'nspaco-fqcn',
            FqcnRepository::class,
            array($fileRepository, $parserFactory)
        );

        $this->setSearchPath();
    }

    abstract protected function setSearchPath();

    protected function getFQNameForClass($className)
    {
        $fqns = $this->fqnsService->findInFor($this->searchPath, $className);
        $tokens = explode('\\', array_shift($fqns)); array_pop($tokens);
        return implode('\\', $tokens);
    }

    protected function beforeCreate($name, $ns, &...$args)
    {
        return "{$ns}\\{$name}";
    }

    abstract protected function processName($name);

    public function create($name, ...$args) {
        $name = $this->processName($name);
        $ns = $this->getFQNameForClass($name);
        $fullName = $this->beforeCreate($name, $ns, $args);

        return (class_exists($fullName)) ? app()->create($fullName, $args) : null;
    }
}