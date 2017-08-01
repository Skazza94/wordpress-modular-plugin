<?php

namespace WPModular\Foundation\View;


class Singleton
{
    protected static $_INSTANCE = null;

    public static function getInstance()
    {
        return (is_null(static::$_INSTANCE)) ? new static : static::$_INSTANCE;
    }
}