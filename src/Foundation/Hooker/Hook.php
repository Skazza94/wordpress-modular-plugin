<?php
/**
 * Generic class that describes a generic hooker behaviour.
 * Polymorphism GRASP Pattern: same type but different behaviours.
 * Must be inherited to implement the hookSpecific method which executes the WordPress function to hook this module.
 *
 * @author Skazza
 */
namespace WPModular\Foundation\Hooker;

use WPModular\Modules\ModuleDispatcher;
use WPModular\Contracts\Hooker\HookerContract;
use WPModular\Modules\ModuleRegisterer;

abstract class Hook implements HookerContract
{
    /**
     * Facade method used to communicate with other classes.
     * Same behaviour for each Hooker type: we parse the function to execute starting from the string, then we call
     * the polymorphic method hookSpecific that handles specific data of the hooker type.
     *
     * @param array $data YAML data of the hook.
     * @author Skazza
     */
    public function hookModule($data)
    {
        if(!array_key_exists('handler', $data))
            return;

        $handler = $this->parseHandler($data['handler'], $data['namespace']);

        $this->hookSpecific($data, $handler);
    }

    /**
     * Handles specific data of the hooker type and calls the WordPress registerer function.
     *
     * @param array $data YAML data of the hook.
     * @param string|array $handler Already parsed handler function.
     * @author Skazza
     */
    protected abstract function hookSpecific($data, $handler);

    /**
     * Parses the handler, if it's a single element array nothing is done and the first value is returned.
     * If it's a two elements array ("ControllerName", "method" format), it's imploded into a string in
     * "ControllerName~method" format.
     *
     * @param array $handler Array which contains the handler function, can be a single element array (function)
     * or a two elements array ("ControllerName", "method" format).
     * @return array|string|null The parsed handler.
     * @author Skazza
     */
    protected function parseHandler($handler, $namespace)
    {
        if(is_null($handler) || empty($handler))
            return null;

        if(array_key_exists('class', $handler) && array_key_exists('method', $handler)) { /* This is a ("ControllerName", "method") type */
            $id = app()->singleton('mod-reg', ModuleRegisterer::class)->registerModule($namespace . '\\' . $handler['class'], (array_key_exists('properties', $handler)) ? $handler['properties'] : array());
            return array(ModuleDispatcher::class, $id . '~' . $handler['method']);
        }

        return $handler['function'];
    }
}
