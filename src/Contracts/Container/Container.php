<?php
namespace WPModular\Contracts\Container;

interface Container
{
    public function register($id, $className, $arguments, $needsSingleton = false);
    public function singletonAndGet($className, $id = null, $arguments = array());
    public function createAndGet($className, $arguments = array());
    public function get($id);
    public function has($id);
}