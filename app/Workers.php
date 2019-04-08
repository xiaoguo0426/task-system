<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/8
 * Time: 10:02
 */

namespace App;

use Think\Db;

class Workers
{
    private static $workers = [];

    public static function run($job)
    {

        $job_id = $job->getId();//job id

        echo $job_id . PHP_EOL;

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
        $d = $data['data'];
        $m = $method;
        go(function () use ($obj, $m, $d) {
            $obj->$m($d);
        });

        echo 2222;
        echo PHP_EOL;

        $task = [
            'site_id' => $data['data']['siteID'],
            'job_id' => $job_id,
            'data' => $job->getData(),
            'create_time' => date('Y-m-d H:i:s')
        ];

        $data = Db::name('jobs')->insert($task);

    }

}