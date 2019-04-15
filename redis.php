<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/15
 * Time: 18:12
 */
for ($i = 0; $i < 10; $i++) {
    go(function () {
        $redis = new \Swoole\Coroutine\Redis();
        $redis->connect('127.0.0.1', 6379);
        $val = $redis->get('name');

        var_dump($val);
    });
}
