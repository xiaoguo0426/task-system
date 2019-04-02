<?php
/**
 * Created by PhpStorm.
 * User: xiaoguo
 * Date: 19-4-2
 * Time: 下午9:08
 */
require 'vendor/autoload.php';

use App\Pheanstalkd;

$pheanstalkd = Pheanstalkd::getInstance();

$res = $pheanstalkd->stats();

var_dump(json_encode($res));