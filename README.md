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
    $ php ./task stats
        
    $ php ./task listTubes

```