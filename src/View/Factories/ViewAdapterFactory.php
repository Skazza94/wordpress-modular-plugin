<?php
namespace WPModular\View\Factories;

use WPModular\Foundation\Factories\Factory;

class ViewAdapterFactory extends Factory
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function processName($name)
    {
        return ucfirst(strtolower($name)) . 'Adapter';
    }
}
