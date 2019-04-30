<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/18
 * Time: 10:48
 */
require __DIR__ . '/../vendor/autoload.php';

$redis = require __DIR__ . '/../config/' . 'redis.php';

\App\Redis::setConfig($redis);

$siteID = 6688;
$userID = 10086;
echo \App\WxAppFormID::getFormId($siteID, $userID);