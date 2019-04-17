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
     */
    public function paySuccess($nickname, $orderNo, $orderItems)
    {

        $instance = $this->app->getTemplateMessageInstance();

        //构建消息
        $send = $instance->send([
            'touser' => $this->openID,
            'template_id' => $this->templateID,
            'page' => $this->page,
            'form_id' => $this->formID,
            'data' => [
                'keyword1' => $nickname,
                'keyword2' => $orderNo,
                'keyword3' => $orderNo
            ],
        ]);

        $send['errcode'];

    }

}