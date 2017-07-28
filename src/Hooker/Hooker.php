<?php
/**
 * Main class used to hook specific plugin modules into
 * WordPress core. All the hooks are read from an YAML file contained into a "modules" subfolder.
 *
 * @author Skazza
 */
namespace WPModular\Hooker;

use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;
use WPModular\Hooker\Factories\HookerFactory;

class Hooker
{
    private $config = null;

    public function __construct()
    {
        $this->config = app('config')->all('hooker');
    }

    /**
     * Registers each module into WordPress core using the YAML configurations file.
     *
     * @author Skazza
     */
    public function hookPlugin()
    {
        /* Get all "modules" subfolders. */
        $filesystem = app('filesystem')->storage('plugin');
        $subfolders = $filesystem->listContents($this->config['modules_path']);

        /* Iterates over them */
        foreach($subfolders as $folder) {
            $hooks = $this->readConfigFile($folder['path']); /* Read the config file into the "modules" subfolder */

            if(is_null($hooks))
                continue;

            foreach($hooks as $hook) { /* Process each declared hook. */
                $type = (string) $hook['type']; /* Get "type" attribute of the hook */
                if(is_null($type) || empty($type)) /* Skip if empty */
                    continue;

                /* Create an Hooker subclass instance starting from the "type" value */
                /* Each Hooker subclass handles a different type of registration (action, filter, etc) */
                $hooker =  HookerFactory::getInstance()->create($type);
                if(!is_null($hooker))
                    $hooker->hookModule($hook);
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
    private function readConfigFile($folder)
    {
        $filesystem = app('filesystem')->storage('plugin');
        $fileName = $folder . DIRECTORY_SEPARATOR . $this->config['config_name'] . '.' . $this->config['config_format']; /* Build the complete path + filename */

        if(!$filesystem->has($fileName)) /* If it's not there, exit */
            return null;

        $hooks = app('cache')->remember(sha1($fileName), app('env')->get('CACHE_MINUTES'), function() use ($filesystem, $fileName) {
            try {
                return Yaml::parse($filesystem->read($fileName));
            } catch(ParseException $e) {
                return null;
            }
        });

        return $hooks;
    }
}