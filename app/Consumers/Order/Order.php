<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/15
 * Time: 16:32
 */

namespace App\Consumers\Order;
class Order
{

    public function createOrder($data)
    {

        var_dump($data);
        go(function () {
            $redis = new \Swoole\Coroutine\Redis();
            $redis->connect('127.0.0.1', 6379);
            $val = $redis->get('name');

            var_dump($val);
        });

    }

    public function payOrder($data)
    {

    }

}