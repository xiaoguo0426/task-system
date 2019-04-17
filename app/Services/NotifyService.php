<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/16
 * Time: 14:26
 */

namespace App\Services;

use App\Models\Notify;

class NotifyService
{

    public function getNotifyByType($type)
    {
        return Notify::where('type', $type)->where('status', 1)->field('canSms,canMiniApp,canOfficialAccount,canStationMsg')->find();
    }

}