<?php
/**
 * Created by PhpStorm.
 * User: xiaoguo
 * Date: 19-4-2
 * Time: 下午9:24
 */

require 'vendor/autoload.php';

use App\Pheanstalkd;

$pheanstalkd = Pheanstalkd::getInstance();

$tubeName = 'testTube';

while (1) {
    $job = $pheanstalkd->watch($tubeName)->reserve();
    var_dump($job);
    $pheanstalkd->delete($job);
}
