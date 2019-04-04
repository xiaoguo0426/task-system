<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/4
 * Time: 16:34
 */

namespace App;


class Config
{
    private static $config;

    public static function set($key, $value = '')
    {
        self::$config[$key] = $value;
    }

    public static function get($key, $default = null)
    {
        if (!strpos($key, '.')) {
            return isset(self::$config[$key]) ? self::$config[$key] : $default;
        } else {
            list($a, $b) = explode('.', $key);
            return isset(self::$config[$a][$b]) ? self::$config[$a][$b] : $default;
        }
    }

    public static function getAll()
    {
        return self::$config;
    }

    public static function has($key)
    {
        if (!strpos($key, '.')) {
            return isset(self::$config[$key]);
        } else {
            list($a, $b) = explode('.', $key);
            return isset(self::$config[$a][$b]);
        }
    }

    public static function del($key)
    {
        unset(self::$config[$key]);
    }

    public static function clear()
    {
        self::$config = [];
    }

    public static function load(array $config)
    {
        self::$config = $config;
    }

    public static function loadFile($filePath)
    {
        if (file_exists($filePath)) {
            $config = require $filePath;
            if (!is_array($config)) {
//                throw new \Exception($config . ' this file return value is not an array');
                $config = [];
            }

            self::load($config);

            return true;
        }
        return false;
    }

}