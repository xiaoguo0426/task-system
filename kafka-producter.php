<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/10
 * Time: 17:40
 */

$rk = new RdKafka\Producer();
$rk->setLogLevel(LOG_ERR);
$rk->addBrokers("127.0.0.1");



$topic = $rk->newTopic("test");

$tube = 'Order';

$cur_date = date('Y-m-d H:i:s');

$actions = ['payOrder'];

$orders = [
    '201904231648214313077',
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

    $topic->produce(RD_KAFKA_PARTITION_UA, 0, json_encode($data));
}