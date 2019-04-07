<?php
/**
 * Created by PhpStorm.
 * User: xiaoguo
 * Date: 19-4-2
 * Time: 下午9:08
 */
require 'vendor/autoload.php';

//use App\Log;
use App\Pheanstalkd;

$pheanstalkd = Pheanstalkd::getInstance();

$tube = 'User';

$cur_date = date('Y-m-d H:i:s');

$actions = ['login', 'pay', 'test'];


for ($i = 0; $i < 60; $i++) {

    $randKey = array_rand($actions);

    $data = [
        'module' => $tube,
        'node' => 'User',
        'action' => $actions[$randKey],
        'data' => [
            'siteID' => 6688,
            'userID' => mt_rand(1000, 9999),
            'nickname' => '锅锅锅',
            'source' => 'miniapp',
            'create_time' => $cur_date
        ]
    ];

    $data['index'] = $i;
    $job = $pheanstalkd->useTube($tube)->put(json_encode($data));
}
