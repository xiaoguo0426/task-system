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
        //TODO
        echo 'login';
    }

    public function pay($data)
    {
        echo 'pay';
    }

    public function test($data)
    {
        echo 'test';
    }

}