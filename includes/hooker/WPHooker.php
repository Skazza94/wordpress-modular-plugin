<?php
/**
 * Main static class used to hook specific plugin modules into
 * WordPress core. All the hooks are read from an YAML file contained into a "modules" subfolder.
 *
 * @author Skazza
 */
class WPHooker {
    /**
     * @var string The YAML file name of the configuration.
     */
    private static $YAML_FILENAME = 'config';
    /**
     * @var string Path to plugin modules.
     */
    private static $COMPONENTS_PATH = PLUGIN_PATH . 'modules' . DIRECTORY_SEPARATOR;

    /**
     * Registers each module into WordPress core using the YAML configurations file.
     *
     * @author Skazza
     */
    public static function hookPlugin() {
        /* Get all "modules" subfolders. */
        $subfolders = array_slice(scandir(static::$COMPONENTS_PATH), 2);

        /* Iterates over them */
        foreach($subfolders as $folder) {
            $hooks = static::readConfigFile($folder); /* Read the config file into the "modules" subfolder */

            if(is_null($hooks))
                continue;

            foreach($hooks as $hook) { /* Process each declared hook. */
                $type = (string) $hook['type']; /* Get "type" attribute of the hook */
                if(is_null($type) || empty($type)) /* Skip if empty */
                    continue;

                /* Create an Hooker subclass instance starting from the "type" value */
                /* Each Hooker subclass handles a different type of registration (action, filter, etc) */
                $hookerName = ucfirst(strtolower($type)) . 'Hooker';
                if(class_exists($hookerName)) {
                    $hooker = new $hookerName;
                    $hooker->hookModule($hook);
                }
            }
        }
    }

    /**
     * Loads the YAML file from a "modules" subfolder.
     *
     * @param string $folder "modules" subfolder where search the configuration YAML file.
     * @return null|array Parsed YAML file. On errors, null is returned.
     * @author Skazza
     */
    private static function readConfigFile($folder) {
        $fullPath = static::$COMPONENTS_PATH . $folder . DIRECTORY_SEPARATOR; /* Build the path */
        if(!is_dir($fullPath)) /* If it's not a folder, exit */
            return null;

        $fileName = $fullPath . static::$YAML_FILENAME . '.yml'; /* Build the complete path + filename */
        if(!file_exists($fileName)) /* If it's not there, exit */
            return null;

        $hooks = Spyc::YAMLLoad($fileName); /* YAML file found, read it */

        if(empty($hooks)) /* It's not an YAML file or it's bad formatted */
            return null;

        return $hooks;
    }
}