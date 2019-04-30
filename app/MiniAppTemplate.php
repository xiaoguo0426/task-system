<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/17
 * Time: 10:43
 */

namespace App;

class MiniAppTemplate
{

    private $templateID;

    private $openID;

    private $formID;

    private $page;

    private $app;

    public function __construct($siteID)
    {
        $wxApp = new WxApp($siteID);

        $this->app = $wxApp;

    }

    public function setTemplateID($templateID)
    {
        $this->templateID = $templateID;
    }

    public function setOpenID($openID)
    {
        $this->openID = $openID;
    }

    public function setFormID($formID)
    {
        $this->formID = $formID;
    }

    public function setPage($page)
    {
        $this->page = $page;
    }

    /**
     * 支付成功
     * @param $orderMoney
     * @param $orderItemsText
     * @param $orderTime
     * @param $remark
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     */
    public function paySuccess($orderMoney, $orderItemsText, $orderTime, $remark)
    {

        $instance = $this->app->getTemplateMessageInstance();

        //构建消息
        $send = $instance->send([
            'touser' => $this->openID,
            'template_id' => $this->templateID,
            'page' => $this->page,
            'form_id' => $this->formID,
            'data' => [
                'keyword1' => $orderTime,
                'keyword2' => $orderItemsText,
                'keyword3' => $orderMoney,
                'keyword4' => $remark,
            ],
        ]);
        var_dump($send);
    }

    /**
     * 物流订单发货 小程序模板消息
     * @param $orderNo          string   订单号
     * @param $orderMoney       float    订单金额
     * @param $orderItemsText   string   订单商品简述
     * @param $deliveryNo       string   物流订单号
     * @param $deliverName      string   物流公司名称
     */
    public function delivery($orderNo, $orderMoney, $orderItemsText, $deliveryNo, $deliverName)
    {
        $instance = $this->app->getTemplateMessageInstance();

        //构建消息
        $send = $instance->send([
            'touser' => $this->openID,
            'template_id' => $this->templateID,
            'page' => $this->page,
            'form_id' => $this->formID,
            'data' => [
                'keyword1' => $orderMoney,
                'keyword2' => $orderItemsText,
                'keyword3' => $deliverName,
                'keyword4' => $deliveryNo,
                'keyword5' => $orderNo,
            ],
        ]);

    }
}