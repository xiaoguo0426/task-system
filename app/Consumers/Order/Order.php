<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/15
 * Time: 16:32
 */

namespace App\Consumers\Order;

use App\Constants;
use App\Log;
use App\MiniAppTemplate;
use App\NotifyConstants;
use App\Pheanstalkd;
use App\Prefix;
use App\Redis;
use App\Services\NotifyMiniAppService;
use App\Services\NotifyService;
use App\Services\NotifySmsService;
use App\Services\NotifyUserRecordService;
use App\Services\OrderService;
use App\Services\SiteUserService;
use App\Services\WxAppService;
use App\UserSms;
use App\WebSocketClient;
use App\WxAppFormID;

class Order
{

    public function createOrder($data)
    {


    }

    public function payOrder($data)
    {

        $siteID = $data['siteID'];
        $userID = $data['userID'];
        $orderID = $data['orderID'];

    }

    public function delivery($data)
    {
        $siteID = $data['siteID'];
        $userID = $data['userID'];
        $orderID = $data['orderID'];

    }

}