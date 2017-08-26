<?php
/**
 * Second level of abstraction from concrete classes, this Hooker subclass
 * defines a type of hookers that register a resource (script/style).
 *
 * @author Skazza
 */
namespace WPModular\Foundation\Hooker\ResourceHooks;

use WPModular\Foundation\Hooker\Hook;

abstract class ResourceHook extends Hook
{
    /**
     * @var string Version of the plugin.
     */
    protected $VERSION = null;

    /**
     * Basic constructor to init the VERSION constant.
     *
     * @author Skazza
     */
    public function __construct()
    {
        $this->VERSION = config('wp_modular.version');
    }

    /**
     * Abstract method implemented from Hooker class.
     * It reads all the information of a style/script from the YAML, sanitizes them and
     * register it into WordPress core using the callWPRegisterer method.
     *
     * @param array $data YAML data of the hook.
     * @param string|array $handler Not used here.
     * @return boolean If everything as been hooked or not.
     * @author Skazza
     */
    protected function hookSpecific($data, $handler)
    {
        if(!array_key_exists('id', $data) || empty($data['id'])) /* If no id is defined, exit */
            return false;
        $id = (string) $data['id'];

        if(!array_key_exists('url', $data) || empty($data['url'])) /* If there's no url for the resource, exit */
            return false;
        $url = PLUGIN_PATH . ((string) $data['url']); /* Build complete url for the resource */

        /* If there are dependecies, get the array, if not default empty array. */
        $dependencies = (array_key_exists('dependencies', $data)) ? array_filter(array_unique($data['dependencies'])) : array();

        $pages = (array_key_exists('pages', $data) && !empty($data['pages'])) ? array_filter(array_unique($data['pages'])) : array('wp');
        foreach($pages as $page) {
            $page = ($page === 'frontend') ? 'wp' : $page;
            if(!in_array($page, array('wp', 'login', 'admin'))) /* Can be only one of those 3 values */
                continue;

            $this->callWPRegisterer($id, $url, $dependencies, $page, $data); /* Call the real WordPress function to register this action */
        }

        return true;
    }

    /**
     * Abstract method, implemented into ResourceHooker subclasses.
     * This method receives all sanitized parameters and calls the WordPress function to register the resource
     * depending on the hook type.
     *
     * @param string $id Identifier to use for this resource.
     * @param string $url Absolute path of the resource.
     * @param array $dependencies Array of dependencies of this resource.
     * @param string $page In which pages load this resource. Can be only wp, login, admin.
     * @param array $data YAML data of the hook.
     * @author Skazza
     */
    protected abstract function callWPRegisterer($id, $url, $dependencies, $page, $data);
}