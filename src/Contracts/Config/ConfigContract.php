<?php

namespace WPModular\Contracts\Config;

interface ConfigContract
{
    public function get($configString);
    public function has($configString);
    public function all($config);
}