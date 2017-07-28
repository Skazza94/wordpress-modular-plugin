<?php

namespace WPModular\Contracts\Environment;

interface EnvironmentContract
{
    public function load();
    public function get($key);
    public function set($key, $value);
}