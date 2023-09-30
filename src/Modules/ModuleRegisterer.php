<?php

namespace WPModular\Modules;

class ModuleRegisterer
{
    public function registerModule($name, $properties = [])
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
        $properties += ['app' => $app];

        $app->singleton($name, $id, $properties);
    }
}