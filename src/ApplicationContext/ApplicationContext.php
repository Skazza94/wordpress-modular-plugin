<?php

namespace WPModular\ApplicationContext;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use WPModular\Contracts\ApplicationContext\ApplicationContextContract;
use WPModular\Foundation\Exceptions\NotSingletonException;

class ApplicationContext implements ApplicationContextContract
{
    private $container = null;
    private $ROOT = null;

    public function __construct($root)
    {
        $this->ROOT = $root;
        $this->container = new ContainerBuilder;
    }

    public function bootstrap()
    {
        /* Loads the helpers. */
        include_once(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'helpers.php');

        /* Brutally reads the services before loading the proper config manager */
        $services = require_once($this->ROOT . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'services.php');
        foreach($services as $alias => $serviceName)
            $this->register($alias, $serviceName, array($this));

        /* Registers and Handlers ModuleProviders */
        $this->handleModuleProviders();
    }

    private function handleModuleProviders()
    {
        $providers = $this->get('config')->get('hooker.providers');
        if(!empty($providers) && is_array($providers))
            foreach($providers as $provider)
                $this->singleton(array_pop(explode('\\', $provider)), $provider, array($this))->boot();
    }

    private function register($id, $className, $arguments)
    {
        $definition = $this->container->register($id, $className);

        $arguments = (!is_array($arguments)) ? array($arguments) : $arguments;
        if(!empty($arguments))
            foreach($arguments as $argument)
                $definition->addArgument($argument);

        return $definition;
    }

    public function singleton($id, $className, $arguments = array())
    {
        if($this->container->has($id))
            return $this->get($id);

        $this->register($id, $className, $arguments);
        return $this->get($id);
    }

    public function create($className, $arguments = array())
    {
        $id = sha1(microtime() . uniqid());

        $this->register($id, $className, $arguments);
        return $this->get($id);
    }

    public function get($id)
    {
        return $this->container->get($id);
    }

    public function getRootPath()
    {
        return $this->ROOT;
    }
}