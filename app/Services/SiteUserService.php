<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/16
 * Time: 15:21
 */

namespace App\Services;


use App\Models\SiteUser;

class SiteUserService
{

    public function getUserInfo($siteID, $userID, $fields = '*')
    {
        return SiteUser::where('SiteID', $siteID)->where('UserID', $userID)->field($fields)->find();
    }

}