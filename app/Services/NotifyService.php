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
        return Notify::where('type', $type)
            ->where('status', 1)
            ->field('canSms,canMiniApp,canOfficialAccount,canStationMsg')
            ->find();
    }

    /**
     * 用户类型通知
     * @param $siteID
     * @param $type
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getUserNotifyByType($type)
    {
        return Notify::where('to', 1)
            ->where('type', $type)
            ->where('status', 1)
            ->field('canSms,canMiniApp,canOfficialAccount,canStationMsg')
            ->find();
    }

    /**
     * 商家类型通知
     * @param $siteID
     * @param $type
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getTraderNotifyByType($type)
    {
        return Notify::where('to', 2)
            ->where('type', $type)
            ->where('status', 1)
            ->field('canSms,canOfficialAccount,canStationMsg')
            ->find();
    }

}