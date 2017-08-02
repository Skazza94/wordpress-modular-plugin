<?php

namespace WPModular\Contracts\Cron;

interface CronContract
{
    public function registerEvents();
    public function unregisterEvents();
    public function registerCronEvent($tag, $interval);
}