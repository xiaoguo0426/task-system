<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/15
 * Time: 18:12
 */
namespace App;

Class Test
{

    public function index()
    {
        go(function () {
            $redis = new \Swoole\Coroutine\Redis();
            $redis->connect('127.0.0.1', 6379);
            $val = $redis->get('name');

            var_dump($val);
        });
    }

}

for ($i = 0; $i < 10; $i++) {

    echo '111' . PHP_EOL;
    $test = new \App\Test();

    $test->index();
    echo '2222' . PHP_EOL;
}
