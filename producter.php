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

$actions = ['payOrder'];

$orders = [
    '201904231648214313077',
//    '201904151740387125082'
];


for ($i = 0; $i < 1; $i++) {

    $randKey = array_rand($actions);
    $randOrderKey = array_rand($orders);

    $data = [
        'module' => $tube,
        'node' => 'Order',
        'action' => $actions[$randKey],
        'data' => [
            'siteID' => 6688,
            'userID' => 154634,
            'orderID' => $orders[$randOrderKey]
        ]
    ];

    $job = $pheanstalkd->useTube($tube)->put(json_encode($data));
}
