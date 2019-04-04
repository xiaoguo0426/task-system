<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/4
 * Time: 9:58
 */
require 'vendor/autoload.php';

use App\Config;

defined('DS') OR define('DS', DIRECTORY_SEPARATOR);

defined('ROOT_PATH') OR define('ROOT_PATH', __DIR__);

defined('LOG_PATH') OR define('LOG_PATH', ROOT_PATH . DS . 'logs' . DS);

defined('CONF_PATH') OR define('CONF_PATH', ROOT_PATH . DS . 'config' . DS);

$db = require CONF_PATH . 'db.php';

Config::loadFile(CONF_PATH . 'db.php');

//Config::load($db);

$key = 'types';

$default = '123123';

//Config::set($key, $default);

//Config::del('paginate');

//$config = Config::getAll();

$type = Config::get('paginate.type');

var_dump($type);



