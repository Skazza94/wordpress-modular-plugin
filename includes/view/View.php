<?php
/**
 * Static class that parses and renders a view.
 * This is a good abstraction from WordPress, since we've a fixed folder for Plugin Views and also some
 * nice ways to select a subfolder and pass parameters to it.
 *
 * @author Skazza
 */

class View {
    /**
     * @var string Path where views are located.
     */
    private static $PATH_TO_USE = PLUGIN_PATH . 'views';
    
    /**
     * Renders desired view, extracting the "params" array into single variables for
     * better usability.
     * 
     * @param string $viewName Name of the PHP file to render (without php extension).
     * @param string $prefix Subfolder of views folder where PHP file is placed.
     * @param array $params Parameters to pass to the view. These will be extracted as single variables.
     * @author Skazza
     */
    public static function render($viewName, $prefix = '', $params = array()) {
        if(!empty($params))
            extract($params); /* This splits the params associative array into single variables with desired value */
        
        require_once(static::buildViewPath($prefix, $viewName));
    }
    
    /**
     * Checks if prefix have final dir separator, if not add it.
     * Also, sanitizes the final path, removing unnecessary slashes from it.
     *
     * @param string $prefix Subfolder of views folder where PHP file is placed.
     * @param string $viewName Name of the PHP file to render (without php extension).
     * @return string Final path for the PHP file to include, sanitized.
     * @author Skazza
     */
    private static function buildViewPath($prefix, $viewName) {
        $prefixToUse = (substr($prefix, -1) !== DIRECTORY_SEPARATOR) ? $prefix . DIRECTORY_SEPARATOR : $prefix;
        return preg_replace('#' . DIRECTORY_SEPARATOR . '+#', DIRECTORY_SEPARATOR, (static::$PATH_TO_USE . $prefixToUse . $viewName . '.php')); /* Removes unnecessary slashes */
    }
}