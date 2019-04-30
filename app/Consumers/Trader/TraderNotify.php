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
use App\Services\NotifyBusinessRecordService;
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

        //根据订单类型，构建不同短信内容

        //新物流订单
        //新到店订单
        //新同城订单
        /** @var \App\Services\OrderService $orderService */
        $orderService = new OrderService();

        $orderInfo = $orderService->getOrderInfo($siteID, $userID, $orderID);

        $type = '';//消息通知类型
        $businessSmsMethod = '';

        switch ($orderInfo['getProductType']) {
            case Constants::GET_PRODUCT_TYPE_DELIVERY:
                $type = NotifyConstants::ADMIN_ORDER_PAY_SUCCESS;
                $businessSmsMethod = 'userPaySuccess';
                break;
            case Constants::GET_PRODUCT_TYPE_STORE:
                $type = NotifyConstants::ADMIN_ORDER_STORE_SUCCESS;
                $businessSmsMethod = 'userPaySuccessStore';
                break;
            case Constants::GET_PRODUCT_TYPE_CITY:
                //同城配送的，要考虑商家自配的问题..商家自配是否需要发短信
                $type = NotifyConstants::ADMIN_ORDER_CITY_PAY_SUCCESS;
                $businessSmsMethod = 'userPaySuccessCity';
                break;
        }
        //根据消息配置，构建消息内容

        $notifyService = new NotifyService();

        $notify = $notifyService->getTraderNotifyByType($type);

        if ($notify->isEmpty()) {
            return true;
        }

        if ($notify->canSms) {
            //短信提醒

            $notifySmsService = new NotifySmsService();

            $smsNotify = $notifySmsService->getNotifyByType($siteID, $type);
            if (!$smsNotify->isEmpty()) {
                if (1 == $smsNotify->status) {
                    try {
                        //构建短信消息内容
                        $sms = new BusinessSms($siteID);

                        $sms->$businessSmsMethod($orderID);

                    } catch (\Exception $exception) {

                        Log::write('task-sms', $exception->getMessage());
                    }
                }
            }

        }

        if ($notify->canOfficialAccount) {

        }

        $msgConf = Config::get('notify.' . $type);

        if ($msgConf && $notify->canStationMsg) {
            //商家默认开通站内信
            $notifyStationService = new NotifyStationService();
            $notifyStationService->getNotifyByType($siteID, $type);
            if (!$smsNotify->isEmpty()) {
                if (0 == $smsNotify->status){
                    return;//
                }
            }

            //判断站点在线的用户哪些有权限
            $adminPermKey = Prefix::adminPerm($siteID);
            $redis = Redis::getInstance();
            $adminPerms = $redis->hGetAll($adminPermKey);

            $msg = sprintf($msgConf['msg'], 1);
            $redirectUri = !empty($msgConf['redirectUri']) ? $msgConf['redirectUri'] : '';
            if ($adminPerms) {
                $websocket = new WebSocketClient();
                $allowPerms = [
                    'SysAdm',
                    'OrderAdministrationAdm',
                    'OrderGoToTheShopAdm'
                ];
                foreach ($adminPerms as $adminID => $adminPerm) {
                    foreach ($allowPerms as $perm) {
                        if (false === strpos($adminPerm, $perm)) {
                            continue;
                        }
                        //查询绑定clientID
                        $adminUserKey = Prefix::adminUserSocket($siteID, $adminID);
                        echo '后台管理员KEY：';
                        echo $adminUserKey;
                        $clientID = $redis->get($adminUserKey);

                        if ($clientID) {
                            $adminMsgNotify = $websocket->adminMsgNotify($clientID, [
                                'type' => $type,
                                'msg' => $msg,
                                'date' => '今日' . date('H:i'),//推送的数据日期当然是“今日”
                                'redirectUri' => $redirectUri
                            ]);
                            var_dump($adminMsgNotify);

                        }
                    }

                }

            }

            //站内消息保存
            $notifyBusinessRecordService = new NotifyBusinessRecordService();

            $notifyBusinessRecordService->add($siteID, $userID, $type, $redirectUri, json_encode([
                'msg' => $msg
            ]));

        }
        //下面如果还有逻辑，return那里需要修改
    }

}