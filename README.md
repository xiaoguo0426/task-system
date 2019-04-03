# task-system

> base on beanstalkd and swoole

# Usage

```
    producter:
        
        require 'vendor/autoload.php';
        
        use App\Pheanstalkd;
        
        $pheanstalkd = Pheanstalkd::getInstance();
        
        $pheanstalkd->useTube('testTube')->put('test data');
```
```
    comsumer:
    
        $ php ./comsumer.php
        
        *************************************************************
    
        require 'vendor/autoload.php';
        
        use App\Pheanstalkd;
        
        $pheanstalkd = Pheanstalkd::getInstance();
        
        $tubeName = 'testTube';
        
        while (1) {
            $job = $pheanstalkd->watch($tubeName)->reserve();
            //var_dump($job);
            //TODO
            $pheanstalkd->delete($job);
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