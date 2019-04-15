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

$tube = 'Order';

$cur_date = date('Y-m-d H:i:s');

$actions = ['createOrder', 'payOrder'];


for ($i = 0; $i < 100; $i++) {

    $randKey = array_rand($actions);

    $data = [
        'module' => $tube,
        'node' => 'Order',
        'action' => $actions[$randKey],
        'data' => [
            'siteID' => 6688,
            'userID' => mt_rand(1000, 9999),
        ]
    ];

    $data['index'] = $i;
    $job = $pheanstalkd->useTube($tube)->put(json_encode($data));
}
