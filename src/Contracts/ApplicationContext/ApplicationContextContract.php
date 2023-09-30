<?php

namespace WPModular\Contracts\ApplicationContext;

interface ApplicationContextContract
{
    public function bootstrap();

    public function singleton($className, $id = null, $arguments = []);

    public function create($className, $arguments = []);

    public function get($id);

    public function getRootPath();
}