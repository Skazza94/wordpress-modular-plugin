<?php
/**
 * Main class used to hook specific plugin modules into
 * WordPress core. All the hooks are read from an YAML file contained into a "modules" subfolder.
 *
 * @author Skazza
 */
namespace WPModular\Hooker;

use League\Flysystem\Filesystem;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;
use WPModular\Hooker\Factories\HookerFactory;

class Hooker
{
    private $namespace = null;
    /** @var Filesystem */
    private $filesystem = null;

    public function __construct($namespace, $filesystem)
    {
        $this->namespace = $namespace;
        $this->filesystem = $filesystem;
    }

    /**
     * Registers each module into WordPress core using the YAML configurations file.
     *
     * @author Skazza
     */
    public function hookModules()
    {
        /* Creates a flat array with all modules subfolders + single modules subfolders */
        $flatten = $this->flatFoldersArray($this->getSubFolders());

        /* Iterates over them */
        foreach($flatten as $folder) {
            $hooks = $this->readConfigFile($folder['path']); /* Read the config file into the "modules" subfolder */

            if(is_null($hooks))
                continue;

            foreach($hooks as $hook) { /* Process each declared hook. */
                $type = (string) $hook['type']; /* Get "type" attribute of the hook */
                if(is_null($type) || empty($type)) /* Skip if empty */
                    continue;

                $hook['namespace'] = $this->namespace;

                /* Create an Hooker subclass instance starting from the "type" value */
                /* Each Hooker subclass handles a different type of registration (action, filter, etc) */
                $hooker =  HookerFactory::getInstance()->create($type);
                if(!is_null($hooker))
                    $hooker->hookModule($hook);
            }
        }
    }

    /**
     * Retrieves all the subfolders of a given path.
     *
     * @param string $path Starting path for subfolders listing.
     * @return array An array containing all the path subfolders.
     * @author Skazza
     */
    private function getSubFolders($path = '')
    {
        return array_filter($this->filesystem->listContents($path), function($value) {
            return $value['type'] === 'dir';
        });
    }

    /**
     * Recursively creates a flat array with all the subfolders of a root folder.
     *
     * @param array $array Current array which contains subfolders.
     * @return array The flatten array with all subfolders.
     * @author Skazza
     */
    private function flatFoldersArray($array)
    {
        $flat = [];

        foreach($array as $value)
            $flat = array_merge($flat, [$value], $this->flatFoldersArray($this->getSubFolders($value['path'])));

        return $flat;
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
        $filesystem = $this->filesystem;
        $fileName = $folder . DIRECTORY_SEPARATOR . config('hooker.config_name') . '.' . config('hooker.config_format'); /* Build the complete path + filename */

        if(!$filesystem->has($fileName)) /* If it's not there, exit */
            return null;

        if(env('USE_CACHE'))
            return cache()->remember(sha1($fileName), config('wp_modular.cache.expires'), function () use ($filesystem, $fileName) {
                $this->parseYaml($filesystem->read($fileName));
            });
        else
            return $this->parseYaml($filesystem->read($fileName));
    }

    private function parseYaml($file)
    {
        try {
            return Yaml::parse($file);
        } catch (ParseException $e) {
            return null;
        }
    }
}