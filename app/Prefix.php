<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/24
 * Time: 10:36
 */

namespace App;

/**
 * 前缀管理
 * Class Prefix
 * @package App
 */
class Prefix
{
    /**
     * 消息前缀
     * @param $siteID
     * @param $userID
     */
    public static function msg($siteID, $userID)
    {
        return Config::get('prefix.msg') . $siteID . '::' . md5($userID);
    }

    public static function adminPerm($siteID)
    {
        return Config::get('prefix.adminPerm') . $siteID;
    }

    public static function adminUserSocket($siteID, $adminID)
    {
        return Config::get('prefix.adminUser') . $siteID . '::' . md5($adminID);
    }

}