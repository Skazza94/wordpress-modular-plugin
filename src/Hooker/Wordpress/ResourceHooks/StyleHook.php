<?php
/**
 * Registers a CSS file into WordPress core.
 *
 * @author Skazza
 */
namespace WPModular\Hooker\Wordpress\ResourceHooks;

use WPModular\Foundation\Hooker\ResourceHooks\ResourceHook;

class StyleHook extends ResourceHook
{
    /**
     * Abstract method implemented from ResourceHooker class.
     * Registers a CSS file into WordPress core using wp_enqueue_style function.
     *
     * @param string $id Identifier to use for this resource.
     * @param string $url Absolute path of the resource.
     * @param array $dependencies Array of dependencies of this resource.
     * @param string $page In which pages load this resource. Can be only wp, login, admin.
     * @param array $data YAML data of the hook.
     * @author Skazza
     */
    protected function callWPRegisterer($id, $url, $dependencies, $page, $data)
    {
        $media = (array_key_exists('media', $data) || empty($data['media'])) ? 'all' : (string) $data['media']; /* If media tag is declared, read its value. If not set default to 'all'. */
        add_action($page . '_enqueue_scripts', function() use ($id, $url, $dependencies, $media) { /* We need to enqueue the style into the 'wp_enqueue_scripts' action! */
            wp_enqueue_style($id, $url, $dependencies, $this->VERSION, $media);
        });
    }
}