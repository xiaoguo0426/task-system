<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/15
 * Time: 16:32
 */

namespace App\Consumers\Order;

use App\Log;
use App\MiniAppTemplate;
use App\NotifyConstants;
use App\Services\NotifyMiniAppService;
use App\Services\NotifyService;
use App\Services\NotifySmsService;
use App\Services\SiteUserService;
use App\Sms;

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

        $notify = $notifyService->getNotifyByType($type);

        $userService = new SiteUserService();

        $user = $userService->getUserInfo($siteID, $userID, 'Mobile as mobile,NickName as nickname,BindWxApp as openID');

        $mobile = $user['mobile'];

        $nickname = $user['nickname'];

        $openID = $user['openID'];

        if ($notify->canSms) {
            //查询是否开启了手机短信
            $notifySmsService = new NotifySmsService();

            $smsNotify = $notifySmsService->getNotifyByType($siteID, $type);
            if (!empty($smsNotify)) {
                if (1 == $smsNotify->status) {
                    try {
                        //构建短信消息内容
                        $sms = new Sms($siteID);

                        $sms->paySuccess($mobile, $nickname, $orderID);

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

            if (!empty($miniAppNotify)) {
                if (1 == $miniAppNotify->status && '' !== $miniAppNotify->templateID) {
                    try {

                        $miniappTemplate = new MiniAppTemplate($siteID);

                        $miniappTemplate->setFormID('7f59313c8c0f449eab05a79875de89ba');

                        $miniappTemplate->setOpenID($openID);

                        $miniappTemplate->setPage('pages/shop/index');

                        $miniappTemplate->setTemplateID($miniAppNotify->templateID);

                        $miniappTemplate->paySuccess($user['nickname'], $orderID);

                    } catch (\Exception $exception) {

                        Log::write('task-sms', $exception->getMessage());
                    }

                }
            }

        }

//        if ($notify->canOfficialAccount) {
//
//        }

        if ($notify->canStationMsg) {
            //查询是否开启了站内信

        }
    }

}