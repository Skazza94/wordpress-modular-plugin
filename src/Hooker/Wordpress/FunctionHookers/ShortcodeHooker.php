<?php
/**
 * Registers a "shortcode" into WordPress core.
 *
 * @author Skazza
 */
namespace WPModular\Hooker\Wordpress\FunctionHookers;

use WPModular\Foundation\Hooker\Wordpress\FunctionHookers\FunctionHooker;

class ShortcodeHooker extends FunctionHooker
{
    /**
     * Abstract method implemented from FunctionHooker class.
     * Registers a "shortcode" into WordPress core using add_shortcode function.
     *
     * @param string $tag Shortcode name to use when calling this shortcode.
     * @param string|array $handler Already parsed handler function.
     * @param int $priority Not used here.
     * @param int $args Not used here.
     * @author Skazza
     */
    protected function callWPRegisterer($tag, $handler, $priority, $args)
    {
        add_shortcode($tag, $handler);
    }
}