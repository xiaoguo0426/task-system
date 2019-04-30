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

        //根据消息配置，构建消息内容
        $type = NotifyConstants::USER_ORDER_PAY_SUCCESS;//支付成功

        $notifyService = new NotifyService();

        $notify = $notifyService->getUserNotifyByType($type);

        if ($notify->isEmpty()) {
            return true;
        }

        $userService = new SiteUserService();

        $user = $userService->getUserInfo($siteID, $userID, 'Mobile as mobile,NickName as nickname,BindWxApp as openID');

        if ($user->isEmpty()) {
            return true;
        }

        $mobile = $user['mobile'];

        $openID = $user['openID'];

        $orderService = new OrderService();

        $msgOrderText = $orderService->getMsgOrderText($siteID, $userID, $orderID);

        if ($notify->canSms && $mobile) {
            //查询是否开启了手机短信
            $notifySmsService = new NotifySmsService();

            $smsNotify = $notifySmsService->getNotifyByType($siteID, $type);
            if (!$smsNotify->isEmpty()) {
                if (1 == $smsNotify->status) {
                    try {
                        //构建短信消息内容
                        $sms = new UserSms($siteID);
                        $sms->paySuccess($mobile, $msgOrderText);

                    } catch (\Exception $exception) {

                        Log::write('task-sms', $exception->getTraceAsString());
                    }
                }
            }
        }
        if ($notify->canMiniApp) {
            //查询是否开启了小程序模板消息
            $notifyMiniAppService = new NotifyMiniAppService();

            $miniAppNotify = $notifyMiniAppService->getNotifyByType($siteID, $type);
            if (!$miniAppNotify->isEmpty()) {
                if (1 == $miniAppNotify->status && '' !== $miniAppNotify->templateID) {
                    //判断当前是否有可用formID
                    $formID = WxAppFormID::getFormId($siteID, $userID);
                    if ('' !== $formID) {
                        try {
                            $orderInfo = $orderService->getOrderInfo($siteID, $userID, $orderID);

//                            $orderMoney = bcadd($orderInfo->money, $orderInfo->freightMoney, 2);
                            $orderMoney = $orderInfo->money + $orderInfo->freightMoney;
                            $miniappTemplate = new MiniAppTemplate($siteID);

                            $miniappTemplate->setFormID($formID);

                            $miniappTemplate->setOpenID($openID);

                            $miniappTemplate->setPage('pages/order/orderdetails/index?id=' . $orderID . '&getproducttype=' . $orderInfo->getProductType);

                            $miniappTemplate->setTemplateID($miniAppNotify->templateID);

                            $miniappTemplate->paySuccess($orderMoney, $msgOrderText, $orderInfo->crTime, $orderInfo->remark);

                        } catch (\Exception $exception) {

                            var_dump($exception->getTrace());

                            Log::write('task-wechat', $exception->getTraceAsString());
                        }
                    }
                }
            }

        }

//        if ($notify->canOfficialAccount) {
//
//        }

        /****************************************************************/
        //商家通知
        //投递到Trader频道处理
        $tube = Constants::TUBE_TRADER;
        $node = 'TraderNotify';
        $pheanstalkd = Pheanstalkd::getInstance();
        $pheanstalkd->useTube($tube)->put(json_encode([
            'module' => $tube,
            'node' => $node,
            'action' => __FUNCTION__,
            'data' => [
                'siteID' => $siteID,
                'userID' => $userID,
                'orderID' => $orderID
            ]
        ]));

    }

    public function delivery($data)
    {
        $siteID = $data['siteID'];
        $userID = $data['userID'];
        $orderID = $data['orderID'];

        //根据消息配置，构建消息内容
        $type = NotifyConstants::USER_ORDER_TRANSSHIP;//物流订单 商家发货

        $notifyService = new NotifyService();

        $notify = $notifyService->getUserNotifyByType($type);

        if ($notify->isEmpty()) {
            return true;
        }

        $userService = new SiteUserService();

        $user = $userService->getUserInfo($siteID, $userID, 'Mobile as mobile,NickName as nickname,BindWxApp as openID');

        if ($user->isEmpty()) {
            return true;
        }

        $mobile = $user['mobile'];//用户手机号

//        $nickname = $user['nickname'];//用户昵称

        $openID = $user['openID'];

        $orderService = new OrderService();

        $orderInfo = $orderService->getOrderInfo($siteID, $userID, $orderID);

        $msgOrderText = $orderService->getMsgOrderText($siteID, $userID, $orderID);

        $wxappService = new WxAppService();

        $wxapp = $wxappService->getMiniAppInfo($siteID);

        if ($wxapp->isEmpty()) {
            return true;
        }

        $page = '/pages/order/orderdetails/index?id=' . $orderID . '&getproducttype=' . $orderInfo['getProductType'];//跳转路径

        $deliveryNo = $orderInfo['invoiceNo'];
        $deliveryName = $orderInfo['logisticsName'];

        if ($notify->canSms && $mobile) {
            //查询是否开启了手机短信
            $notifySmsService = new NotifySmsService();

            $smsNotify = $notifySmsService->getNotifyByType($siteID, $type);
            if (!$smsNotify->isEmpty()) {
                if (1 == $smsNotify->status) {
                    try {
                        //构建短信消息内容
                        $sms = new UserSms($siteID);
//                        $sms->delivery($mobile, $msgOrderText, $wxapp['appName']);

                    } catch (\Exception $exception) {

                        Log::write('task-sms', $exception->getMessage());
                    }
                }
            }
        }

        if ($notify->canMiniApp) {
            //查询是否开启了小程序模板消息
            $notifyMiniAppService = new NotifyMiniAppService();

            $miniAppNotify = $notifyMiniAppService->getNotifyByType($siteID, $type);
            if (!$miniAppNotify->isEmpty()) {
                if (1 == $miniAppNotify->status && '' !== $miniAppNotify->templateID) {
                    //判断当前是否有可用formID
                    $formID = WxAppFormID::getFormId($siteID, $userID);
                    if ('' !== $formID) {
                        try {

                            $orderMoney = bcadd($orderInfo['money'], $orderInfo['freightMoney'], 2);

                            $miniappTemplate = new MiniAppTemplate($siteID);

                            $miniappTemplate->setFormID($formID);

                            $miniappTemplate->setOpenID($openID);

                            $miniappTemplate->setPage($page);

                            $miniappTemplate->setTemplateID($miniAppNotify->templateID);

//                            $miniappTemplate->delivery($orderID, $orderMoney, $msgOrderText, $deliveryNo, $deliveryName);

                        } catch (\Exception $exception) {

                            Log::write('task-wechat', $exception->getMessage());
                        }
                    }
                }
            }

        }

        if ($notify->canStationMsg) {
            //查询对应的clientID;
            $redis = Redis::getInstance();
            echo Prefix::msg($siteID, $userID);
            $clientID = $redis->get(Prefix::msg($siteID, $userID));

            $notifyUserRecordService = new NotifyUserRecordService();
var_dump($clientID);
            if ($clientID) {
                //在线推送
                //查询未读数量
                $count = $notifyUserRecordService->getNoReadCount($siteID, $userID);
                $count += 1;
                try {
                    $websocket = new WebSocketClient();
                    $websocket->msgNotify($clientID, [
                        'count' => $count
                    ]);
                } catch (\Exception $exception) {

                }

            }

            $image = $orderService->orderImage;

            //保存记录
            $notifyUserRecordService->add($siteID, $userID, $type, Constants::LAYOUT_A, [
                'imgList' => ((false !== strpos($image, ',')) ? explode(',', $image) : [$image]),
                'title' => Constants::TITLE_DELIVERY,
                'main' => $msgOrderText,
                'footer' => '运单号：' . $deliveryNo
            ], $page);

        }
    }

}