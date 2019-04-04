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

$data = [
    'module' => $tube,
    'node' => 'User',
    'action' => 'login',
    'data' => [
        'siteID' => 6688,
        'userID' => 10086,
        'nickname' => '锅锅锅',
        'source' => 'miniapp',
        'create_time' => $cur_date
    ]
];
for ($i = 0; $i < 1000; $i++) {
    $data['index'] = $i;
    $job = $pheanstalkd->useTube($tube)->put(json_encode($data));
}
