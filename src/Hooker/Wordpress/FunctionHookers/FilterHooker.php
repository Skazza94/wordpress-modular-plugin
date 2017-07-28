<?php
/**
 * Registers a "filter" into WordPress core.
 *
 * @author Skazza
 */
namespace WPModular\Hooker\Wordpress\FunctionHookers;

use WPModular\Foundation\Hooker\Wordpress\FunctionHookers\FunctionHooker;

class FilterHooker extends FunctionHooker
{
    /**
     * Abstract method implemented from FunctionHooker class.
     * Registers a "filter" into WordPress core using add_filter function.
     *
     * @param string $tag WordPress tag to hook the function/method.
     * @param string|array $handler Already parsed handler function.
     * @param int $priority Priority of the filter when executing all the functions of a specified tag.
     * @param int $args Arguments to pass to the function.
     * @author Skazza
     */
    protected function callWPRegisterer($tag, $handler, $priority, $args)
    {
        add_filter($tag, $handler, $priority, $args);
    }
}