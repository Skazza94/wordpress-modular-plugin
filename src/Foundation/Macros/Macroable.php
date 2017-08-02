<?php

namespace WPModular\Foundation\Macros;

use WPModular\Foundation\Exceptions\BadMethodCallException;

trait Macroable
{
    protected $mixins = array();

    public function __call($name, $arguments)
    {
        if(empty($this->mixins))
            throw new BadMethodCallException("No mixins registered into this proxy.");

        foreach($this->mixins as $mixin)
            if(method_exists($mixin, $name))
                return call_user_func_array(array($mixin, $name), $arguments);

        throw new BadMethodCallException("Method {$name} is not registered into the mixins.");
    }

    protected function addMixin($object)
    {
        $key = sha1(get_class($object));
        if(!array_key_exists($key, $this->mixins))
            $this->mixins[$key] = $object;
    }

    protected function addMixins($objects)
    {
        foreach($objects as $object)
            $this->addMixin($object);
    }
}