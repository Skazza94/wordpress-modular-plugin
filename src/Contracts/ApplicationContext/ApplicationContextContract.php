<?php

namespace WPModular\Contracts\ApplicationContext;

interface ApplicationContextContract
{
    public function bootstrap();
    public function singleton($id, $className, $arguments = array());
    public function create($className, $arguments = array());
    public function get($id);
    public function getRootPath();
}