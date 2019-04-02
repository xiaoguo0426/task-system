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

    public static function getInstance()
    {

        if (empty(self::$pheanstalkd)) {
echo 11;
            self::$pheanstalkd = Pheanstalk::create(self::BEANSTALKD_HOST);
        }
echo 222;
        return self::$pheanstalkd;

    }

}