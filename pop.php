<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/8
 * Time: 10:30
 */
$http = new Swoole\Http\Server("127.0.0.1", 9501);

$http->on("request", function ($request, $response) {
    var_dump($request);
});

$http->start();