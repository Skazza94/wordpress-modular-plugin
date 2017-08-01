<?php

namespace WPModular\Modules;

use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use WPModular\Foundation\View\Singleton;

class ModuleRegisterer extends Singleton
{
    public function registerModule($name, $properties = array())
    {
        $id = $this->generateId($name, $properties);

        try {
            app()->getService($id);
        } catch(ServiceNotFoundException $e) {
            $module = $this->registerInAppContext($id, $name);

            if(!empty($properties))
                $this->registerProperties($module, $properties);
        }

        return $id;
    }

    private function generateId($name, $properties)
    {
        $sArgs = (!empty($properties)) ? base64_encode(serialize($properties)) : 'N';
        return sha1($name . $sArgs);
    }

    /**
     * @param $id
     * @param $name
     * @return \Symfony\Component\DependencyInjection\Definition
     */
    private function registerInAppContext($id, $name)
    {
        $app = app();
        $ns = config('hooker.module_ns');

        $module = $app->registerService($id, "{$ns}\\{$name}");
        $module->addArgument($app);

        return $module;
    }

    private function registerProperties(Definition $module, $properties)
    {
        foreach ($properties as $propertyName => $propertyValue) {
            $propertyName = str_replace(' ', '', ucwords(str_replace(array('-', '_'), ' ', $propertyName)));

            $module->addMethodCall(
                "set{$propertyName}",
                (is_array($propertyValue)) ? $propertyValue : array($propertyValue)
            );
        }
    }
}