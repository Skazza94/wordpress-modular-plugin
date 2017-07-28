<?php
/*
 * Lookup for the namespace starting from an "Hooker" class name.
 * We need this because we have no reference for the Hookers classes namespace,
 * so we have to do a lookup easily and without any problems.
 *
 * @author Skazza
 */
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
