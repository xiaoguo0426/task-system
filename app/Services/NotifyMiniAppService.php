<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/16
 * Time: 15:21
 */

namespace App\Services;


use App\Models\NotifyMiniApp;

class NotifyMiniAppService
{

    public function getNotifyByType($siteID, $type)
    {
        return NotifyMiniApp::where('SiteID', $siteID)->where('notifyType', $type)->where('status', 1)->field('id,templateID,status')->find();
    }

}