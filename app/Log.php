<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/3
 * Time: 17:48
 */

namespace App;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Log
{

    private static $allMethods = [
        'error',
        'info',
        'debug',
        'notice',
        'log',
        'warn'
    ];

    public function __construct()
    {
        self::$logName = date('Y-m-d');
    }

    private static function getLogName($type)
    {
        return date('Y-m-d') . '-' . $type . '.log';
    }

    public static function __callStatic($method, $arguments)
    {

        if (!in_array($method, self::$allMethods)) {
            throw new \Exception($method . ' method don\'t exist');
        }

        return self::writeLog($method, var_export($arguments, true));
    }

    private static function writeLog($logType, $msg)
    {

        try {

            $logName = self::getLogName($logType);

            $logger = new Logger($logName);

            $upper = strtoupper($logType);

            $logPath = LOG_PATH . $logName;

            $logger->pushHandler(new StreamHandler($logPath, $upper));

            return $logger->$logType($msg);

        } catch (\Exception $exception) {
            file_put_contents($logPath, $exception->getMessage());
        }

    }

    public static function write($logName, $content)
    {

        try {

            $type = 'debug';

            $logName = self::getLogName($logName . '-' . $type);

            $logger = new Logger($logName);

            $upper = strtoupper($type);

            $logPath = LOG_PATH . $logName;

            $logger->pushHandler(new StreamHandler($logPath, $upper));

            return $logger->$type($content);

        } catch (\Exception $exception) {
            file_put_contents($logPath, $exception->getMessage());
        }

    }

}