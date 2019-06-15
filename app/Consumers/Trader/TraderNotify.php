<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/18
 * Time: 17:46
 */

namespace App\Consumers\Trader;

use App\BusinessSms;
use App\Config;
use App\Constants;
use App\NotifyConstants;
use App\Prefix;
use App\Redis;
use App\Services\NotifyBusinessRecordLogService;
use App\Services\NotifyService;
use App\Services\NotifySmsService;
use App\Services\NotifyStationService;
use App\Services\OrderService;
use App\WebSocketClient;

class TraderNotify
{
    public function __construct()
    {

    }

    /**
     * 用户支付成功，商家消息处理
     */
    public function payOrder($data)
    {
        $siteID = $data['siteID'];
        $userID = $data['userID'];
        $orderID = $data['orderID'];

    }

}