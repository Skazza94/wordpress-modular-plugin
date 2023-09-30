<?php

namespace WPModular\Cron;

use WPModular\Contracts\Cron\CronContract;

class CronManager implements CronContract
{
    private $cronEvents = [];
    private $cronIntervals = [];

    public function __construct()
    {
        $file = app()->getRootPath() . DIRECTORY_SEPARATOR . config('wp_modular.plugin_slug') . '.php';

        register_activation_hook($file, [$this, 'registerEvents']);
        register_deactivation_hook($file, [$this, 'unregisterEvents']);

        add_filter('cron_schedules', [$this, 'registerIntervals']);
    }

    public function registerIntervals($schedules)
    {
        $schedules += $this->cronIntervals;
        return $schedules;
    }

    public function registerEvents()
    {
        foreach ($this->cronEvents as $eventName => $eventInterval) {
            list($recurrence, $timestamp) = $this->makeTimestamp($eventInterval);
            wp_schedule_event($timestamp, $recurrence, $eventName);
        }
    }

    private function makeTimestamp($eventInterval)
    {
        list($recurrence, $hour) = array_pad(explode('@', $eventInterval, 2), 2, null);

        $recurrence = trim($recurrence);
        $hour = (!is_null($hour)) ? trim($hour) : $hour;

        $currentTime = time(); /* We save it so we have no problems with checks */
        $timestamp = (!is_null($hour)) ? (new \DateTime("{$hour}:00", new \DateTimeZone(get_option('timezone_string'))))->getTimestamp() : $currentTime;
        if ($timestamp < $currentTime)
            $timestamp += 24 * 60 * 60; /* We have to add a day */

        return [$recurrence, $timestamp];
    }

    public function unregisterEvents()
    {
        foreach ($this->cronEvents as $eventName => $eventInterval)
            wp_clear_scheduled_hook($eventName);
    }

    public function registerCronEvent($tag, $interval)
    {
        $this->cronEvents += [$tag => $interval];
    }

    public function addCronInterval($name, $minutes)
    {
        if (in_array($name, ['hourly', 'twicedaily', 'daily']))
            return;

        $this->cronIntervals[$name] = [
            'interval' => abs($minutes) * 60,
            'display' => "{$minutes} mins"
        ];

        $this->cronIntervals[$name] = array_unique($this->cronIntervals);
    }
}