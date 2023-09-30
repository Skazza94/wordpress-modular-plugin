<?php
/**
 * Registers a JavaScript file into WordPress core.
 *
 * @author Skazza
 */

namespace WPModular\Hooker\Wordpress\ResourceHooks;

use WPModular\Foundation\Hooker\Wordpress\ResourceHooks\ResourceHook;

class ScriptHook extends ResourceHook
{
    /**
     * Abstract method implemented from ResourceHooker class.
     * Registers a JavaScript file into WordPress core using wp_enqueue_script function.
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
        $infooter = (!array_key_exists('infooter', $data) || empty($data['infooter'])) ? false : (bool)$data['infooter']; /* If infooter tag is declared, read its value. If not set default to false. */
        add_action($page . '_enqueue_scripts', function () use ($id, $url, $dependencies, $infooter) { /* We need to enqueue the script into the 'wp_enqueue_scripts' action! */
            wp_enqueue_script($id, $url, $dependencies, $this->VERSION, $infooter);
        });
    }
}