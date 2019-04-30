<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/16
 * Time: 15:21
 */

namespace App\Services;


use App\Models\WxApp;

class WxAppService
{

    /**
     * 获取短信配置信息
     */
    public function getMiniAppInfo($siteID)
    {
        $info = $this->getInfo($siteID, 'AppID as appid,AppSecret as appSecret,Name as appName');

        return $info;
    }

    public function getInfo($siteID, $fields = '*')
    {
        $wxapp = WxApp::where('SiteID', $siteID)->field($fields)->find();
        return $wxapp;
    }

}