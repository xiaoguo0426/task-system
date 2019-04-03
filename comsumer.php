<?php
/**
 * Created by PhpStorm.
 * User: xiaoguo
 * Date: 19-4-2
 * Time: 下午9:24
 */

require 'vendor/autoload.php';

use App\Constants;
use App\Pheanstalkd;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

$pheanstalkd = Pheanstalkd::getInstance();

while (1) {

    try {

        $job = $pheanstalkd->watch(Constants::ACTIVITY_TUBE)
            ->watch(Constants::ORDER_TUBE)
            ->watch(Constants::USER_TUBE)
            ->ignore('default')
            ->reserve();

        $jobID = $job->getId();//job id

        $data = json_decode($job->getData(), true);//消息数据

        $module = $data['module'];

        $node = $data['node'];

        //构建相应的消费者
        $class = "App\\Consumers\\" . $module . "\\" . $node;

        if (!class_exists($class)) {
            $pheanstalkd->bury($job);
            throw new \Exception('Class ' . $class . ' not found!');
        }

        $pheanstalkd->delete($job);

    } catch (\Exception $exception) {

        $logName = date('Y-m-d') . '.log';

        $logPath = './logs';

        $logger = new Logger($logName);

        $logger->pushHandler(new StreamHandler($logPath . '/' . $logName, Logger::ERROR));

        $logger->error($exception->getMessage());

    }

}
