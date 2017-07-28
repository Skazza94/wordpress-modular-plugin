<?php

namespace WPModular\Contracts\ApplicationContext;

interface ApplicationContextContract
{
    public function bootstrap();
    public function registerService($id, $className);
    public function getService($id);
    public function getRootPath();
}