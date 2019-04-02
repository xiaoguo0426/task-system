<?php
/**
 * Created by PhpStorm.
 * User: xiaoguo
 * Date: 19-4-2
 * Time: 下午10:46
 */

require 'vendor/autoload.php';

use App\Pheanstalkd;

$pheanstalkd = Pheanstalkd::getInstance();

$res = $pheanstalkd->stats();

foreach ($res as $key => $value){

    echo $key.'--'.$value.PHP_EOL;

}