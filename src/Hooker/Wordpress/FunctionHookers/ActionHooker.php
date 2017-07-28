<?php
/**
 * Registers an "action" into WordPress core.
 *
 * @author Skazza
 */
namespace WPModular\Hooker\Wordpress\FunctionHookers;

use WPModular\Foundation\Hooker\Wordpress\FunctionHookers\FunctionHooker;

class ActionHooker extends FunctionHooker
{
    /**
     * Abstract method implemented from FunctionHooker class.
     * Registers an "action" into WordPress core using add_action function.
     *
     * @param string $tag WordPress tag to hook the function/method.
     * @param string|array $handler Already parsed handler function.
     * @param int $priority Priority of the action when executing all the functions of a specified tag.
     * @param int $args Arguments to pass to the function.
     * @author Skazza
     */
    protected function callWPRegisterer($tag, $handler, $priority, $args)
    {
        add_action($tag, $handler, $priority, $args);
    }
}