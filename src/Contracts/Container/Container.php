<?php

namespace WPModular\Contracts\Container;

interface Container
{
    public function register($id, $className, $arguments, $needsSingleton = false);

    public function singletonAndGet($className, $id = null, $arguments = []);

    public function createAndGet($className, $arguments = []);

    public function get($id);

    public function has($id);
}