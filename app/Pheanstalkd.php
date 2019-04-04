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

    const BEANSTALKD_HOST = 'beanstalkd';

    const BEANSTALKD_PORT = 11300;

    const BEANSTALKD_TIMEOUT = 10;

    public static function getInstance()
    {

        if (empty(self::$pheanstalkd)) {
            self::$pheanstalkd = Pheanstalk::create(self::BEANSTALKD_HOST, self::BEANSTALKD_PORT, self::BEANSTALKD_TIMEOUT);
        }
        return self::$pheanstalkd;
    }

}