<?php

namespace WPModular\Hooker\Wordpress\CronHooks;

use WPModular\Cron\CronManager;
use WPModular\Hooker\Wordpress\FunctionHooks\ActionHook;

class CronHook extends ActionHook
{
    /**
     * Little alternative version from ActionHook.
     * After hooking the actions, we add them as cron tasks into the CronManager.
     *
     * @param array $data YAML data of the hook.
     * @param string|array $handler Already parsed handler function.
     * @return boolean If everything as been hooked or not.
     * @author Skazza
     */
    protected function hookSpecific($data, $handler)
    {
        $status = parent::hookSpecific($data, $handler);

        if (!$status)
            return false;

        foreach ($data['tags'] as $hook) {
            if (!array_key_exists('tag', $hook) || empty($hook['tag']))
                continue;

            if (!array_key_exists('interval', $hook) || empty($hook['interval']))
                continue;

            app()->singleton(CronManager::class)->registerCronEvent((string)$hook['tag'], (string)$hook['interval']);
        }

        return true;
    }
}