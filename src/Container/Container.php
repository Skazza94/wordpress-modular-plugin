<?php

namespace WPModular\Container;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use WPModular\Contracts\Container\Container as ContainerContract;

class Container implements ContainerContract
{
    private $container = null;

    public function __construct()
    {
        $this->container = new ContainerBuilder;
    }

    public function register($id, $className, $arguments, $needsSingleton = false)
    {
        $definition = $this->container->register($id, $className)
            ->setLazy(true);

        $resolvedArgs = $this->resolveClassConstructorArguments($className, $arguments, $needsSingleton);

        if (!empty($resolvedArgs))
            foreach ($resolvedArgs as $name => $argumentValue)
                $definition->setArgument($name, $argumentValue);

        return $definition;
    }

    private function resolveClassConstructorArguments($className, $arguments, $needsSingleton)
    {
        $resolvedArgs = [];

        $rClass = new \ReflectionClass($className);
        $ctor = $rClass->getConstructor();
        /* This means there's not constructor argument to inject, return an empty array */
        if (is_null($ctor))
            return $resolvedArgs;

        foreach ($ctor->getParameters() as $parameter) {
            /* A default value for arguments, if they're not resolved in any way */
            /* This will probably trigger an error if an argument is forgotten */
            $argument = null;
            $parameterName = $parameter->getName();

            /* If an argument value is specified in the arguments array, use it */
            if (array_key_exists($parameterName, $arguments))
                $argument = $arguments[$parameterName];
            else { /* If not, check if a class instance is required */
                $parameterClass = $parameter->getClass();

                if (!is_null($parameterClass)) { /* It's a class to be resolved, use DI */
                    $parameterClassName = $parameterClass->getName();

                    if ($needsSingleton)
                        $id = $this->singleton($parameterClassName);
                    else
                        $id = $this->create($parameterClassName);

                    $argument = new Reference($id);
                } else { /* Not a class, check if default value can be used */
                    /* Any other case will use previous null! */
                    if ($parameter->isOptional()) /* Use default value */
                        $argument = $parameter->getDefaultValue();
                }
            }

            $resolvedArgs[$parameterName] = $argument;
        }

        return $resolvedArgs;
    }

    private function singleton($className, $id = null, $arguments = [])
    {
        /* First check by id (if != null) */
        if (!is_null($id)) {
            if ($this->container->has($id))
                return $id;
        }

        /* If not, check by class name */
        $classHash = sha1($className);
        if ($this->container->has($classHash))
            return $classHash;

        $newId = (!is_null($id)) ? $id : $classHash;

        $this->register($newId, $className, $arguments, true);
        return $newId;
    }

    public function has($id)
    {
        return $this->container->has($id);
    }

    private function create($className, $arguments = [])
    {
        $id = sha1(microtime() . uniqid());

        $this->register($id, $className, $arguments);
        return $id;
    }

    public function singletonAndGet($className, $id = null, $arguments = [])
    {
        $id = $this->singleton($className, $id, $arguments);
        return [$id, $this->get($id)];
    }

    public function get($id)
    {
        return $this->container->get($id);
    }

    public function createAndGet($className, $arguments = [])
    {
        $id = $this->create($className, $arguments);
        return [$id, $this->get($id)];
    }
}