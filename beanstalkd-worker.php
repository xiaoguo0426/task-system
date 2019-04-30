<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/3
 * Time: 17:56
 */
require 'vendor/autoload.php';

use App\Config;
use App\Constants;
use App\Pheanstalkd;
use App\Redis;
use Think\Db;

defined('DS') OR define('DS', DIRECTORY_SEPARATOR);

defined('ROOT_PATH') OR define('ROOT_PATH', __DIR__);

defined('CONF_PATH') OR define('CONF_PATH', ROOT_PATH . DS . 'config' . DS);

defined('LOG_PATH') OR define('LOG_PATH', ROOT_PATH . DS . 'logs' . DS);

$files = scandir(CONF_PATH);

$config = [];
foreach ($files as $file) {
    if ('.' !== $file && '..' !== $file) {
        $conf = require CONF_PATH . $file;
        $config = array_merge($config, $conf);
    }
}

Config::load($config);
Db::setConfig(Config::get('db'));
Redis::setConfig(Config::get('redis'));

class Worker
{

    private static $workers = [];

    public static function run()
    {
        $pheanstalkd = Pheanstalkd::getInstance();

        while (1) {

            try {

                $job = $pheanstalkd->watch(Constants::TUBE_ACTIVITY)
                    ->watch(Constants::TUBE_ORDER)
                    ->watch(Constants::TUBE_TRADER)
                    ->watch(Constants::TUBE_USER)
                    ->ignore('default')
                    ->reserve();

                $t1 = microtime(true);//开始时间

                $job_id = $job->getId();//job id

                $data = json_decode($job->getData(), true);//消息数据

                $module = $data['module'];

                $node = $data['node'];

                $method = $data['action'];

                //构建相应的消费者
                $class = "App\\Consumers\\" . $module . "\\" . $node;

                if (!class_exists($class)) {
                    throw new \Exception('Class ' . $class . ' not found!');
                }

                $index = md5($class);
                if (!isset(self::$workers[$index])) {
                    $obj = new $class();
                    self::$workers[$index] = $obj;
                } else {
                    $obj = self::$workers[$index];
                }

                //$obj = new $class();
                $obj->$method($data['data']);

                $t2 = microtime(true);//结束时间

                echo '任务ID：' . $job_id . '耗时' . round($t2 - $t1, 3) . '秒' . PHP_EOL;

                $task = [
                    'site_id' => $data['data']['siteID'],
                    'job_id' => $job_id,
                    'data' => $job->getData(),
                    'create_time' => date('Y-m-d H:i:s')
                ];

                $data = Db::name('notify_jobs')->insert($task);

                $pheanstalkd->delete($job);

            } catch (\Pheanstalk\Exception\DeadlineSoonException $exception) {

            } catch (\Exception $exception) {

//                $pheanstalkd = Pheanstalkd::getInstance();
//
//                $pheanstalkd->bury($job);
                var_dump($exception->getMessage());
                var_dump($exception->getTraceAsString());

                var_dump($exception->getLine());

//                Log::error('无法处理的消息，请确认后再试~~');
//                Log::error($job->getData());
//                Log::error($exception->getMessage());
            }

        }
    }

    public static function init()
    {
        // Only for cli.
        if (php_sapi_name() != "cli") {
            exit("only run in command line mode \n");
        }
        // Only for linux

        // Check extension
    }

}

Worker::run();

