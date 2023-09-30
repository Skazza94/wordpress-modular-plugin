<?php
/**
 * Second level of abstraction from concrete classes, this Hooker subclass
 * defines a type of hookers that register a specific callback for a WordPress tag.
 *
 * @author Skazza
 */

namespace WPModular\Foundation\Hooker\Wordpress\FunctionHooks;

use WPModular\Foundation\Hooker\Hook;

abstract class FunctionHook extends Hook
{
    /**
     * Abstract method implemented from Hooker class.
     * It reads all the tags from the YAML, iterates over them and
     * registers them into WordPress core using the callWPRegisterer method.
     *
     * @param array $data YAML data of the hook.
     * @param string|array $handler Already parsed handler function.
     * @return boolean If everything as been hooked or not.
     * @author Skazza
     */
    protected function hookSpecific($data, $handler)
    {
        if (is_null($handler))
            return false;

        if (!array_key_exists('tags', $data) || empty($data['tags'])) /* If there are no WP tags, this can't be hooked */
            return false;

        foreach ($data['tags'] as $hook) { /* Iterate over WP tags */
            if (!array_key_exists('tag', $hook) || empty($hook['tag'])) /* If empty, skip */
                continue;
            $tag = (string)$hook['tag'];

            $priority = (array_key_exists('priority', $hook) && !empty($hook['priority'])) ? (int)$hook['priority'] : 10; /* Read the priority, if it's defined. If not set default value to 10. */
            $args = (array_key_exists('args', $hook) && !empty($hook['args'])) ? (int)$hook['args'] : 1; /* Read the arguments to pass to the function, if it's defined. If not set default value to 1. */

            $this->callWPRegisterer($tag, $handler, $priority, $args); /* Call the real WordPress function to register this action */
        }

        return true;
    }

    /**
     * Abstract method, implemented into FunctionHooker subclasses.
     * This method receives all sanitized parameters and calls the WordPress function to register the action
     * depending on the hook type.
     *
     * @param string $tag WordPress tag to hook the function/method.
     * @param string|array $handler Already parsed handler function.
     * @param int $priority Priority when executing all the functions of a specified tag.
     * @param int $args Arguments to pass to the function.
     * @author Skazza
     */
    protected abstract function callWPRegisterer($tag, $handler, $priority, $args);
}