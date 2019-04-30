<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/18
 * Time: 17:34
 */

namespace App;

/**
 * 商家通知
 * Class BusinessSms
 * @package App
 */
class BusinessSms extends Sms
{

    public function __construct($siteID)
    {
        parent::__construct($siteID);

        if ('' == $this->smsReceiveMobile) {
            throw new \Exception('商家手机号为空');
        }
    }

    /**
     * 用户支付成功，通知商家尽快发货【物流订单】
     */
    public function userPaySuccess($orderID)
    {

        $mobile = $this->smsReceiveMobile;//商家收信手机号

        $content = '您有一笔新的物流订单待发货，订单号<%s>,请尽快处理！【%s】 ';//签名一定要放到后面！！！
        $content = sprintf($content, $orderID, $this->smsSign);

        $this->send($mobile, $content);
    }

    /**
     * 到店自提
     * @param $orderID
     */
    public function userPaySuccessStore($orderID)
    {

        $mobile = $this->smsReceiveMobile;//商家收信手机号

        $content = '您有一笔新的到店订单待备货，订单号<%s>,请尽快准备货物等待买家上门取货！【%s】 ';//签名一定要放到后面！！！
        $content = sprintf($content, $orderID, $this->smsSign);

        $this->send($mobile, $content);
    }

    /**
     * 同城配送
     * @param $orderID
     */
    public function userPaySuccessCity($orderID)
    {

        $mobile = $this->smsReceiveMobile;//商家收信手机号

        $content = '您有一笔新的同城配送订单待接单，订单号<%s>,请尽快准备货物给外卖小哥！【%s】 ';//签名一定要放到后面！！！
        $content = sprintf($content, $orderID, $this->smsSign);

        $this->send($mobile, $content);
    }

}