<?php

namespace WPModular\Cron;

use WPModular\Contracts\Cron\CronContract;

class CronManager implements CronContract
{
    private $cronEvents = array();
    private $cronIntervals = array();

    public function __construct()
    {
        $file = app()->getRootPath() . DIRECTORY_SEPARATOR . env('PLUGIN_SLUG') . '.php';

        register_activation_hook($file, array($this, 'registerEvents'));
        register_deactivation_hook($file, array($this, 'unregisterEvents'));

        add_filter('cron_schedules', array($this, 'registerIntervals'));
    }

    public function registerIntervals($schedules)
    {
        $schedules += $this->cronIntervals;
        return $schedules;
    }

    public function registerEvents()
    {
        foreach($this->cronEvents as $eventName => $eventInterval) {
            list($recurrence, $timestamp) = $this->makeTimestamp($eventInterval);
            wp_schedule_event($timestamp, $recurrence, $eventName);
        }
    }

    public function unregisterEvents()
    {
        foreach($this->cronEvents as $eventName => $eventInterval)
            wp_clear_scheduled_hook($eventName);
    }

    public function registerCronEvent($tag, $interval)
    {
        $this->cronEvents += array($tag => $interval);
    }

    public function addCronInterval($name, $minutes)
    {
        if(in_array($name, array('hourly', 'twicedaily', 'daily')))
            return;

        $this->cronIntervals[$name] = array(
            'interval' => ((int) $minutes) * 60,
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