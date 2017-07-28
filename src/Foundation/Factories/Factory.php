<?php

namespace WPModular\Foundation\Factories;

use Gnugat\NomoSpaco\File\FileRepository;
use Gnugat\NomoSpaco\FqcnRepository;
use Gnugat\NomoSpaco\Token\ParserFactory;

abstract class Factory
{
    protected static $_INSTANCE = null;
    protected $fqnsService = null;
    protected $searchPath = null;

    protected function __construct()
    {
        $this->fqnsService = new FqcnRepository(new FileRepository(), new ParserFactory());
        $this->setSearchPath();
    }

    abstract protected function setSearchPath();

    public static function getInstance()
    {
        return (is_null(static::$_INSTANCE)) ? new static() : static::$_INSTANCE;
    }

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

        return (class_exists($fullName)) ? new $fullName(...$args) : null;
    }
}