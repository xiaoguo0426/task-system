<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/8
 * Time: 9:51
 */
while (1){
    echo "main start\n";
    go(function () {
        echo "coro " . co::getcid() . " start\n";
        co::sleep(1); //switch at this point
        echo "coro " . co::getcid() . " end\n";
    });
    echo "end\n";
}
