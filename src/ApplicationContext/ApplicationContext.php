<?php

namespace WPModular\ApplicationContext;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use WPModular\Contracts\ApplicationContext\ApplicationContextContract;

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
            $this->registerService($alias, $serviceName)
                 ->addArgument($this);
    }

    public function registerService($id, $className)
    {
        return $this->container->register($id, $className);
    }

    public function getService($id)
    {
        return $this->container->get($id);
    }

    public function getRootPath()
    {
        return $this->ROOT;
    }
}