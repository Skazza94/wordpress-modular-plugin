<?php

namespace WPModular\Wordpress;

use WPModular\Foundation\Services\Service;

class WordpressService extends Service
{
    private $cronEvents = array();
    private $env = null;

    public function bootstrap()
    {
        $this->env = $this->app->getService('env');
    }

    public function getPluginName()
    {
        return $this->env->get('PLUGIN_SLUG');
    }

    public function getTextDomain()
    {
        return $this->env->get('PLUGIN_NAME');
    }

    public function getPluginVersion()
    {
        return $this->env->get('VERSION');
    }

    public function getTranslation($text)
    {
        return __($text, $this->getTextDomain());
    }

    public function registerCronEvent($tag, $interval)
    {
        $this->cronEvents += array($tag => $interval);
    }

    public function getCronEvents()
    {
        return $this->cronEvents;
    }
}