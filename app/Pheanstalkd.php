<?php
/**
 * Created by PhpStorm.
 * User: xiaoguo
 * Date: 19-4-2
 * Time: 下午9:31
 */

namespace App;


use Pheanstalk\Pheanstalk;

class Pheanstalkd
{

    private static $pheanstalkd;

    const HOST = '127.0.0.1';

    const PORT = 8889;

    const TIMEOUT = 10;

    public static function getInstance()
    {

        if (empty(self::$pheanstalkd)) {
            self::$pheanstalkd = Pheanstalk::create(self::HOST, self::PORT, self::TIMEOUT);
        }
        return self::$pheanstalkd;
    }

}