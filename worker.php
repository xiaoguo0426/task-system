<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/3
 * Time: 17:56
 */
require 'vendor/autoload.php';

use App\Constants;
use App\Pheanstalkd;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Think\Db;

class Worker
{

    public static function run()
    {
        $pheanstalkd = Pheanstalkd::getInstance();

        while (1) {

            try {

                $job = $pheanstalkd->watch(Constants::ACTIVITY_TUBE)
                    ->watch(Constants::ORDER_TUBE)
                    ->watch(Constants::USER_TUBE)
                    ->ignore('default')
                    ->reserve();
echo 111;
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

                (new $class())->$method($data['data']);

                $task = [
                    'site_id' => $data['data']['siteID'],
                    'job_id' => $job_id,
                    'data' => $job->getData(),
                    'create_time' => date('Y-m-d H:i:s')
                ];

                $data = Db::name('jobs')->insert($task);

                $pheanstalkd->delete($job);
            } catch (\Pheanstalk\Exception\DeadlineSoonException $exception) {

            } catch (\Exception $exception) {

                $pheanstalkd->bury($job);

                $logName = date('Y-m-d') . '.log';

                $logPath = './logs';

                $logger = new Logger($logName);

                $logger->pushHandler(new StreamHandler($logPath . '/' . $logName, Logger::ERROR));

                $logger->error($exception->getTraceAsString());

            }

        }
    }

}


Worker::run();