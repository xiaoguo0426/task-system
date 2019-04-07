<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/3
 * Time: 11:10
 */

namespace App\Consumers\User;

class User
{
    public static $count = 0;

    public function login($data)
    {
        self::$count++;
        echo 'count:' . self::$count . PHP_EOL;
        //TODO
        echo 'login';
//        sleep(1);
    }

    public function pay($data)
    {
        echo 'pay';
//        sleep(2);
    }

    public function test($data)
    {
        echo 'test';
//        sleep(3);
    }

}