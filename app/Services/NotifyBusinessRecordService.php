<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/16
 * Time: 14:26
 */

namespace App\Services;

use App\Models\NotifyBusinessRecord;

class NotifyBusinessRecordService
{

    public function add($siteID, $userID, $type, $page, $content)
    {
        $data = [
            'SiteID' => $siteID,
            'UserID' => $userID,
            'type' => $type,
            'redirectUri' => $page,
            'content' => json_encode($content),
            'isRead' => 0,
            'createTime' => time()
        ];

        $model = new NotifyBusinessRecord();

        return $model->save($data);

    }

}