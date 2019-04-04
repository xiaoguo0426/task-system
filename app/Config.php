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
        return self::has($key) ? self::$config[$key] : $default;
    }

    public static function getAll()
    {
        return self::$config;
    }

    public static function has($key)
    {
        return isset(self::$config[$key]);
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
            $config = require CONF_PATH . 'db.php';
            self::load($config);
            return true;
        }
        return false;
    }

}