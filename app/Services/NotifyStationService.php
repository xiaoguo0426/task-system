<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/16
 * Time: 15:21
 */

namespace App\Services;

use App\Models\NotifyStation;

/**
 * 站内信配置
 * Class NotifyStationService
 * @package App\Services
 */
class NotifyStationService
{
    public function getNotifyByType($siteID, $type)
    {
        return NotifyStation::where('siteID', $siteID)->where('notifyType', $type)->where('status', 1)->field('id,status')->find();
    }
}