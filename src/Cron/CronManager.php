<?php

namespace WPModular\Cron;

use WPModular\Contracts\Cron\CronContract;

class CronManager implements CronContract
{
    private $cronEvents = array();
    private $cronIntervals = array();

    public function __construct()
    {
        if(app()->isLoaded())
            return;

        $file = app()->getRootPath() . DIRECTORY_SEPARATOR . config('wp_modular.plugin_slug') . '.php';

        register_activation_hook($file, array($this, 'registerEvents'));
        register_deactivation_hook($file, array($this, 'unregisterEvents'));

        add_filter('cron_schedules', array($this, 'registerIntervals'));
    }

    public function registerIntervals($schedules)
    {
        if(app()->isLoaded())
            return;

        $schedules += $this->cronIntervals;
        return $schedules;
    }

    public function registerEvents()
    {
        if(app()->isLoaded())
            return;

        foreach($this->cronEvents as $eventName => $eventInterval) {
            list($recurrence, $timestamp) = $this->makeTimestamp($eventInterval);
            wp_schedule_event($timestamp, $recurrence, $eventName);
        }
    }

    public function unregisterEvents()
    {
        if(app()->isLoaded())
            return;

        foreach($this->cronEvents as $eventName => $eventInterval)
            wp_clear_scheduled_hook($eventName);
    }

    public function registerCronEvent($tag, $interval)
    {
        if(app()->isLoaded())
            return;

        $this->cronEvents += array($tag => $interval);
    }

    public function addCronInterval($name, $minutes)
    {
        if(app()->isLoaded())
            return;

        if(in_array($name, array('hourly', 'twicedaily', 'daily')))
            return;

        $this->cronIntervals[$name] = array(
            'interval' => abs($minutes) * 60,
            'display' => "{$minutes} mins"
        );

        array_unique($this->cronIntervals);
    }

    private function makeTimestamp($eventInterval)
    {
        list($recurrence, $hour) = array_pad(explode('@', $eventInterval, 2), 2, null);

        $recurrence = trim($recurrence);
        $hour = (!is_null($hour)) ? trim($hour) : $hour;

        $timestamp = (!is_null($hour)) ? (new \DateTime("{$hour}:00", new \DateTimeZone(get_option('timezone_string'))))->getTimestamp() : time();

        return array($recurrence, $timestamp);
    }
}