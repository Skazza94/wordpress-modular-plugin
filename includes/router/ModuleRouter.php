<?php
/**
 * This class is a troll, really.
 * Each hooked module tries to call a static method in this class. Since there are no methods declared,
 * the __callStatic is triggered. The method string we pass is actually a ControllerName~method format.
 * So we split it, create an instance of the class, cache it for later uses and call the method.
 *
 * This provides a single entry point for each module, and we avoid the creation of tons of class instances
 * when hooking the action.
 *
 * @author Skazza
 */
class ModuleRouter {
    /**
     * @var array Class instances created will be saved here, for caching purposes.
     */
    private static $_instances = array();

    /**
     * Catches calls to undefined static methods.
     * Transparently creating a module class instance.
     * Provides a single entry point for each module, so we avoid usage of a lot of Singletons.
     *
     * @param string $method "ControllerName~method" string format.
     * @param mixed $args Arguments of the method.
     * @return mixed|null Whatever the method returns, null if method isn't declared into the object or there's an error.
     * @author Skazza
     */
    public static function __callStatic($method, $args) {
        list($className, $methodName) = array_filter(explode('~', $method, 2), 'strlen'); /* Sanity checking */
        if(is_null($className) || is_null($methodName))
            return null;

        /* We should do this in another method, but this class is a ninja, only this method should exist! */
        $shaClassName = sha1($className);
        if(!array_key_exists($shaClassName, static::$_instances))
            static::$_instances[$shaClassName] = new $className(); /* Cache the instance */

        $obj = static::$_instances[$shaClassName]; /* Retrieve instance from cache */

        if(method_exists($obj, $methodName)) /* If the method is declared into the object, call it. */
            return call_user_func_array(array($obj, $methodName), $args);

        return null; /* Return null if not defined */
    }
}