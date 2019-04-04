<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/4
 * Time: 18:59
 */

namespace App\Consumers\Wechat;


class TemplateMsg
{

    public function send($data)
    {
        go(function ($d) {
//            $redis = new Swoole\Coroutine\Redis();
//            $redis->connect('127.0.0.1', 6379);
//            $val = $redis->get('key');
            sleep(1);
            var_dump($d);

        });

    }

}