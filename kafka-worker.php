<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/24
 * Time: 15:53
 */

require 'vendor/autoload.php';

use App\Config;
use App\Redis;
use Think\Db;
use App\Log;

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
        $consumer = new RdKafka\Consumer();
        $consumer->setLogLevel(LOG_USER);
        $consumer->addBrokers(Config::get('kafka.brokers_list'));

        $topic = $consumer->newTopic("test");
        $topic->consumeStart(0, RD_KAFKA_OFFSET_BEGINNING);

        while (true) {
            $msg = $topic->consume(0, 5000);
            if (null === $msg) {
                continue;
            } elseif ($msg->err) {
                echo $msg->errstr(), "\n";
                continue;
            } else {
                self::consume($msg);
            }
        }
    }

    public static function consume($job)
    {
        try {

            $t1 = microtime(true);//开始时间

            $job_id = $job->offset;//job id

            $data = json_decode($job->payload, true);//消息数据

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
//                $obj = new $class();
            $obj->$method($data['data']);

            $t2 = microtime(true);//结束时间

            echo '任务ID：' . $job_id . '耗时' . round($t2 - $t1, 3) . '秒' . PHP_EOL;

            $task = [
                'site_id' => $data['data']['siteID'],
                'job_id' => $job_id,
                'data' => $job->payload,
                'create_time' => date('Y-m-d H:i:s')
            ];

            $data = Db::name('notify_jobs')->insert($task);
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
            var_dump($exception->getTraceAsString());

            var_dump($exception->getLine());
            Log::error('无法处理的消息，请确认后再试~~');
            Log::error($job->payload);
        }

    }

}

Worker::run();