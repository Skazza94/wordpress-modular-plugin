<?php

namespace WPModular\ApplicationContext;

use WPModular\Container\Container;
use WPModular\Contracts\ApplicationContext\ApplicationContextContract;
use WPModular\Foundation\Modules\ModuleProvider;

class ApplicationContext implements ApplicationContextContract
{
    private $container = null;
    private $ROOT = null;
    private $LOADED = false;

    public function __construct($root)
    {
        $this->ROOT = $root;
        $this->container = new Container;
    }

    public function bootstrap()
    {
        /* If the plugin has been already bootstrapped, exit */
        if ($this->LOADED)
            return;

        /* Loads the helpers. */
        include_once(dirname(__FILE__, 2) . DIRECTORY_SEPARATOR . 'helpers.php');

        /* Brutally reads the services before loading the proper config manager */
        $services = require_once($this->ROOT . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'services.php');
        foreach ($services as $alias => $serviceName) {
            $this->register($alias, $serviceName, ['app' => $this]);

            if ($serviceName::$BOOTLOAD)
                $this->get($alias); /* We have to call the bootstrap method. To do this, we get the instance for the Container so constructor will be called. */
        }

        /* Registers and Handlers ModuleProviders */
        $this->handleModuleProviders();

        $this->LOADED = true;
    }

    private function register($id, $className, $arguments)
    {
        return $this->container->register($id, $className, $arguments);
    }

    public function get($id)
    {
        return $this->container->get($id);
    }

    private function handleModuleProviders()
    {
        $providers = $this->get('config')->get('hooker.providers');

        if (!empty($providers) && is_array($providers))
            foreach ($providers as $provider) {
                $provider = $this->create($provider, ['app' => $this]);

                if ($provider instanceof ModuleProvider && method_exists($provider, 'boot'))
                    $provider->boot();
            }
    }

    public function create($className, $arguments = [])
    {
        list($_, $instance) = $this->container->createAndGet($className, $arguments);
        return $instance;
    }

    public function singleton($className, $id = null, $arguments = [])
    {
        list($_, $instance) = $this->container->singletonAndGet($className, $id, $arguments);
        return $instance;
    }

    public function getRootPath()
    {
        return $this->ROOT;
    }
}