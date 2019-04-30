<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/4
 * Time: 16:04
 */

return [
    //前缀 一般用户redis
    'prefix' => [
        'msg' => 'msg::',
        'adminPerm' => 'adminPerm::',//后台用户权限ws前缀
        'adminUser' => 'adminUser::',//后台用户socket前缀
    ],
    'socket' => [
        'url' => 'https://gw-msg.yz168.cc/',
        'user' => 'yunzhi',
        'pass' => 'mker123'
    ]
];