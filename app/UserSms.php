<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/18
 * Time: 17:38
 */

namespace App;

/**
 * 用户短信
 * Class UserSms
 * @package App
 */
class UserSms extends Sms
{

    /**
     * 未支付短信
     */
    public function noPay()
    {

    }

    /**
     * 支付成功
     */
    public function paySuccess($mobile, $orderItemsText)
    {

        $content = '您购买的<%s> 已支付成功，我们将尽快处理您的订单，感谢您的支持。【%s】 ';//签名一定要放到后面！！！
        $content = sprintf($content, (mb_strlen($orderItemsText) <= 17 ? $orderItemsText : mb_substr($orderItemsText, 0, 17, 'utf-8') . '...'), $this->smsSign);

        $this->send($mobile, $content);
    }

    public function delivery($mobile, $orderItemsText, $miniAppName)
    {
        $content = '您购买的<%s> 订单：已发货，快递小哥正在为您配送中。您可以登录<%s>小程序查看物流信息。【%s】 ';//签名一定要放到后面！！！

        $content = sprintf($content,
            (mb_strlen($orderItemsText) <= 17 ? $orderItemsText : mb_substr($orderItemsText, 0, 17, 'utf-8') . '...'),
            (mb_strlen($miniAppName) <= 10 ? $miniAppName : mb_substr($miniAppName, 0, 10, 'utf-8') . '...'),
            $this->smsSign);

        $this->send($mobile, $content);
    }

}