# task-system

> base on beanstalkd and swoole

# Usage

```

    beanstalkd 
       
       $ ps aux | grep beanstalkd
       
       $ sudo kill -9
       
       $ beanstalkd -l 0.0.0.0 -p 8889 &

    producter:
        
        require 'vendor/autoload.php';
        
        use App\Pheanstalkd;
        
        $pheanstalkd = Pheanstalkd::getInstance();
        
        $tube = 'User';
        
        $cur_date = date('Y-m-d H:i:s');
        
        $data = [
            'module' => $tube,              //模块名称
            'node' => 'User',               //节点名称
            'action' => 'login',            //消费动作
            'data' => [                     //消息数据
                'siteID' => 6688,
                'userID' => 10086,
                'nickname' => '锅锅锅',
                'source' => 'miniapp',
                'create_time' => $cur_date
            ]
        ];
        
        $pheanstalkd->useTube($tube)->put($data);
```
```
    consumer:
    
        $ php ./worker.php
        
        *************************************************************
        
        app\Consumers\User\User.php
       
        <?php
        
        namespace App\Consumers\User;
        
        class User
        {
        
            public function login($data)
            {
                //TODO
            }
        
        }
```


# Tool

```
    $ php ./task stats                  查看beanstalkd状态
        
    $ php ./task listTubes              列出所有管道
    
    $ php ./task clear $tube            清除执行管道的消息
    
    $ php ./task clearAll               清除所有管道的消息
    
    $ php ./task statsTube $tube        列出管道消息
    
    $ php ./task listTubesWatched       列出当前监听的管道
    
    $ php ./task listTubeUsed           列出当前被使用的管道
    
    $ php ./task pauseTube $tube $delay 指定管道设置延迟
    
    $ php ./task resumeTube $tube       取消管道延迟
    
```