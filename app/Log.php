<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/3
 * Time: 17:48
 */

namespace App;


class Log
{
    private static $logName;

    private static $logPath = '';

    public function __construct()
    {
        self::$logName = date('Y-m-d');
    }

    private static function getLogName($type)
    {
        return date('Y-m-d') . '-' . $type . '.log';
    }

    public static function error($msg)
    {

        var_dump(self::$logName);
    }

    public static function warning($msg)
    {

    }

    public function notice($msg)
    {

    }

}