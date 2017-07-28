<?php

namespace WPModular\Hooker\Factories;

use WPModular\Foundation\Factories\Factory;

class HookerFactory extends Factory
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function setSearchPath()
    {
        $this->searchPath = dirname(dirname(__FILE__));
    }

    protected function processName($name)
    {
        return ucfirst(strtolower($name)) . 'Hooker';
    }
}
