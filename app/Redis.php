<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/16
 * Time: 11:18
 */

namespace App;


class Redis
{
    protected static $config = [];

    private static $instance;

    public static function setConfig(array $config)
    {
        self::$config = $config;
    }

    public static function getInstance()
    {

        if (empty(self::$instance)) {

            $config = self::_parseConfig();

            $redis = new \Redis();

            $redis->connect($config['host'], $config['port']);

            $redis->auth($config['auth']);

            self::$instance = $redis;
        }

        return self::$instance;

    }

    private static function _parseConfig()
    {

        if (!empty(self::$config)) {

            $config = [
                'host' => self::$config['host'],
                'port' => self::$config['port'],
                'auth' => self::$config['auth']
            ];

        } else {
            $config = [
                'host' => '127.0.0.1',
                'port' => 6379,
                'auth' => ''
            ];
        }

        return $config;

    }

}