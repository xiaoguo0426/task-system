<?php


require '../vendor/autoload.php';

require '../root.php';

use App\Config;
use App\Redis;


$files = scandir(CONF_PATH);

$config = [];
foreach ($files as $file) {
    if ('.' !== $file && '..' !== $file) {
        $conf = require CONF_PATH . $file;
        $config = array_merge($config, $conf);
    }
}

Config::load($config);

Redis::setConfig(Config::get('redis'));

$redis = Redis::getInstance();

$offset = 2;

echo "今天是第{$offset}天" . PHP_EOL;

$cacheKey = '10086';
//签到
//一年一个用户会占用多少空间呢？大约365/8=45.625个字节，好小，有木有被惊呆？
$redis->setBit($cacheKey, $offset, 1);

//查询签到情况
$bitStatus = $redis->getBit($cacheKey, $offset);
echo 1 == $bitStatus ? '今天已经签到啦' : '还没有签到呢';
echo PHP_EOL;

//计算总签到次数
echo $redis->bitCount($cacheKey) . PHP_EOL;