<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/16
 * Time: 15:21
 */

namespace App\Services;


use App\Models\NotifySms;

class NotifySmsService
{

    public function getNotifyByType($siteID, $type)
    {
        return NotifySms::where('siteID', $siteID)->where('notifyType', $type)->where('status', 1)->field('id,status')->find();
    }

}