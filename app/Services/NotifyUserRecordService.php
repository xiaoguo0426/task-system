<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/16
 * Time: 14:26
 */

namespace App\Services;

use App\Models\NotifyUserRecord;

class NotifyUserRecordService
{

    public function add($siteID, $userID, $type, $layoutID, $content, $page)
    {
        $data = [
            'SiteID' => $siteID,
            'UserID' => $userID,
            'type' => $type,
            'layoutID' => $layoutID,
            'content' => json_encode($content),
            'redirectPage' => $page,
            'isRead' => 0,
            'createTime' => time()
        ];

        $model = new NotifyUserRecord();

        return $model->data($data)->save();
    }

    public function getNoReadCount($siteID, $userID)
    {

        return NotifyUserRecord::where('SiteID', $siteID)
            ->where('UserID', $userID)
            ->where('isRead', 0)
            ->count('*');

    }

}