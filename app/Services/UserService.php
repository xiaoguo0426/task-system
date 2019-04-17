<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/16
 * Time: 15:21
 */

namespace App\Services;


use App\Models\Site;
use App\Models\User;

class UserService
{

    /**
     * 获取短信配置信息
     */
    public function getSmsInfo($siteID)
    {
        $info = $this->getInfo($siteID, 'SMSUser as smsUser,SMSPassword as smsPassword,SMSReceiveMobile as smsReceiveMobile,SMSSign as smsSign');

        return $info;
    }

    public function getInfo($siteID, $fields = '*')
    {

        $site = Site::where('SiteID', $siteID)->field('UserID as userID')->find();

        if (!empty($site)) {

            $userID = $site->userID;

            $user = User::where('UserID', $userID)->field($fields)->find();
            return $user;
        }

        return [];

    }

}