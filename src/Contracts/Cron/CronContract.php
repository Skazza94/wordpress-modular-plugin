<?php

namespace WPModular\Contracts\Cron;

interface CronContract
{
    public function registerCronEvent($tag, $interval);
}