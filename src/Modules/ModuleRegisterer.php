<?php

namespace WPModular\Modules;

use WPModular\Foundation\Support\Singleton;

class ModuleRegisterer extends Singleton
{
    public function registerModule($name, $properties = array())
    {
        $id = $this->generateId($name, $properties);
        $this->registerInAppContext($id, $name, $properties);
        return $id;
    }

    private function generateId($name, $properties)
    {
        $sArgs = (!empty($properties)) ? base64_encode(serialize($properties)) : 'N';
        return sha1($name . $sArgs);
    }

    private function registerInAppContext($id, $name, $properties)
    {
        $app = app();
        $properties += array($app);

        $app->singleton($id, $name, $properties);
    }
}