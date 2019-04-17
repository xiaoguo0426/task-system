<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/16
 * Time: 15:21
 */

namespace App\Services;


use App\Models\Site;

class SiteService
{

    public function getInfo($siteID)
    {
        return Site::where('SiteID', $siteID)->find();
    }

}