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

    private $app;

    private $data;

    public function __construct($siteID)
    {
        $wxApp = new WxApp($siteID);

        $this->app = $wxApp->getTemplateMessageInstance();

    }

    public function send($open_id, $form_id, $template_id, $page)
    {

        $data = [
            'touser' => $open_id,
            'template_id' => $template_id,
            'page' => $page,
            'form_id' => $form_id,
            'data' => $this->data
        ];

        $this->_filterData($data);

        $this->data = [];
        //构建消息
        $send = $this->app->send($data);

        \libs\Log::debug(\json_encode(['data' => $data, 'response' => $send]));
    }

    private function _filterData($data)
    {
        foreach ($data as $key => $value) {
            if (empty($value)) {
                throw new \Exception($key . '不能为空！');
            }
        }
    }

    /**
     * 支付成功
     * @param $orderMoney
     * @param $orderItemsText
     * @param $orderID
     * @return $this
     */
    public function paySuccess($orderMoney, $orderItemsText, $orderID)
    {

        $data = [
            'keyword1' => '￥' . $orderMoney . '元',
            'keyword2' => $orderItemsText,
            'keyword3' => $orderID,
            'keyword4' => '您在订单已支付成功，我们将尽快为您处理订单，感谢您对我们的支持！点击查看详细',
        ];

        $this->data = $data;
        return $this;
    }

    /**
     * 物流订单发货 小程序模板消息
     * @param $orderNo
     * @param $orderMoney
     * @param $orderItemsText
     * @param $deliveryNo
     * @param $deliverName
     * @return $this
     */
    public function delivery($orderNo, $orderMoney, $orderItemsText, $deliveryNo, $deliverName)
    {
        $data = [
            'keyword1' => '￥' . $orderMoney . '元',
            'keyword2' => $orderItemsText,
            'keyword3' => $deliverName,
            'keyword4' => $deliveryNo,
            'keyword5' => $orderNo,
            'keyword6' => '您的订单商家已发货，请密切关注物流动态，不要错过了哦！点击查看详细'
        ];

        $this->data = $data;
        return $this;
    }

    /**
     * 订单确认收货 小程序模板消息
     * @param $orderNo
     * @param $orderMoney
     * @param $orderItemsText
     * @return $this
     */
    public function confirm($orderNo, $orderMoney, $orderItemsText)
    {
        $data = [
            'keyword1' => '￥' . $orderMoney . '元',
            'keyword2' => $orderItemsText,
            'keyword3' => $orderNo,
            'keyword4' => '您的订单已确认收货，感谢您的支持，期待您的下次光临。'
        ];

        $this->data = $data;
        return $this;
    }

    /**
     * 到店订单 核销 小程序模板消息
     * @param $orderNo
     * @param $orderMoney
     * @param $orderItemsText
     * @param $pickUserName
     * @param $pickUserPhone
     * @return $this
     */
    public function writeOff($orderNo, $orderMoney, $orderItemsText, $pickUserName, $pickUserPhone)
    {
        $data = [
            'keyword1' => '￥' . $orderMoney . '元',
            'keyword2' => $orderItemsText,
            'keyword3' => $pickUserName,
            'keyword4' => $pickUserPhone,
            'keyword5' => $orderNo,
            'keyword6' => '您的订单已确认收货，感谢您的支持，期待您的下次光临。'
        ];

        $this->data = $data;
        return $this;
    }

    /**
     * 同城订单 商家已接单、商家拒绝接单、商家配送中 小程序模板消息
     * @param $orderNo
     * @param $orderMoney
     * @param $orderItemsText
     * @return $this
     */
    public function cityOrderReceipt($orderNo, $orderMoney, $orderItemsText)
    {
        $data = [
            'keyword1' => '￥' . $orderMoney . '元',
            'keyword2' => $orderItemsText,
            'keyword3' => $orderNo,
            'keyword4' => '您的订单商家已接单，商家正在为您准备货品，点击查看详细'
        ];

        $this->data = $data;
        return $this;
    }

    /**
     * 同城订单-商家拒绝接单
     * @param $orderNo
     * @param $orderMoney
     * @param $orderItemsText
     * @param $reason
     * @return $this
     */
    public function cityOrderRefuse($orderNo, $orderMoney, $orderItemsText, $reason)
    {
        $data = [
            'keyword1' => '￥' . $orderMoney . '元',
            'keyword2' => $orderItemsText,
            'keyword3' => $orderNo,
            'keyword4' => sprintf('您的订单商家拒绝接单，<%s>，点击查看详细', (mb_strlen($reason) <= 17 ? $reason : mb_substr($reason, 0, 17, 'utf-8') . '...'))
        ];

        $this->data = $data;
        return $this;
    }

    /**
     * 同城订单-配送中
     * @param $orderNo
     * @param $orderMoney
     * @param $orderItemsText
     * @return $this
     */
    public function cityOrderTransShip($orderNo, $orderMoney, $orderItemsText)
    {
        $data = [
            'keyword1' => '￥' . $orderMoney . '元',
            'keyword2' => $orderItemsText,
            'keyword3' => $orderNo,
            'keyword4' => '备注：您的订单正在配送中，商家正在为您准备货品，点击查看详细'
        ];

        $this->data = $data;
        return $this;
    }

    /**
     * 同城订单-完成
     * @param $orderNo
     * @param $orderMoney
     * @param $orderItemsText
     * @return $this
     */
    public function cityOrderFinish($orderNo, $orderMoney, $orderItemsText)
    {

        $data = [
            'keyword1' => '￥' . $orderMoney . '元',
            'keyword2' => $orderItemsText,
            'keyword3' => $orderNo,
            'keyword4' => '您的订单已确认收货，感谢您的支持，期待您的下次光临。'
        ];

        $this->data = $data;
        return $this;
    }

    /**
     * 名片消息模板 访客通知
     * @param $userName
     * @param $visitTime
     * @param $actionText
     * @return $this
     */
    public function visitor($userName, $visitTime, $actionText)
    {
        $data = [
            'keyword1' => $userName,
            'keyword2' => $visitTime,
            'keyword3' => $actionText,
        ];

        $this->data = $data;
        return $this;
    }

    /**
     * 名片消息模板 (访客通知 \ 未读消息)
     * @param $userName
     * @param $visitTime
     * @return $this
     */
    public function unreadMessage($userName, $visitTime)
    {

        $data = [
            'keyword1' => $userName,
            'keyword2' => $visitTime,
            'keyword3' => '你有新的未读信息，点击查看详细',
        ];

        $this->data = $data;
        return $this;
    }


    /**
     * 申请退款
     * @param $orderMoney
     * @param $orderItemsText
     * @param $refundNo
     * @return $this
     */
    public function refund($orderMoney, $orderItemsText, $refundNo)
    {

        $data = [
            'keyword1' => '￥' . $orderMoney . '元',
            'keyword2' => $orderItemsText,
            'keyword3' => $refundNo,
            'keyword4' => '您的退款申请已提交，我们将尽快审核您的订单，点击查看详细',
        ];

        $this->data = $data;
        return $this;
    }

    /**
     * 同意退货
     * @param $orderMoney
     * @param $orderItemsText
     * @param $refundNo
     * @return $this
     */
    public function agreeReturn($orderMoney, $orderItemsText, $refundNo)
    {

        $data = [
            'keyword1' => '￥' . $orderMoney . '元',
            'keyword2' => $orderItemsText,
            'keyword3' => $refundNo,
            'keyword4' => '您的退货申请商家已同意，请尽快完成退货，点击去退货',
        ];

        $this->data = $data;
        return $this;
    }

    /**
     * 同意退款
     * @param $orderMoney
     * @param $orderItemsText
     * @param $refundNo
     * @return $this
     */
    public function refundSuccess($orderMoney, $orderItemsText, $refundNo)
    {

        $data = [
            'keyword1' => '￥' . $orderMoney . '元',
            'keyword2' => $orderItemsText,
            'keyword3' => $refundNo,
            'keyword4' => '您的退款已成功，退款金额已原路返还到您的账户，点击查看详细',
        ];

        $this->data = $data;
        return $this;
    }

    /**
     * 拒绝退款模板发送
     * @param $orderMoney
     * @param $orderItemsText
     * @param $refundNo
     * @param $rejectReason
     * @return $this
     */
    public function refundFail($orderMoney, $orderItemsText, $refundNo, $rejectReason)
    {
        $rejectReasonText = '您的退款失败<%s>，点击查看详细';
        $rejectReasonText = sprintf($rejectReasonText, $rejectReason);

        $data = [
            'keyword1' => '￥' . $orderMoney . '元',
            'keyword2' => $orderItemsText,
            'keyword3' => $refundNo,
            'keyword4' => $rejectReasonText,
        ];

        $this->data = $data;
        return $this;
    }


    /**
     * 同城取消订单 - 通知商家
     * @param $orderMoney
     * @param $orderItemsText
     * @param $refundNo
     * @return $this
     */
    public function cityOrderCancel($orderMoney, $orderItemsText, $refundNo)
    {
        $rejectReasonText = '您的订单已取消，订单金额将原路返还您的账户，点击查看详细，期待您的下次光临。';

        $data = [
            'keyword1' => '￥' . $orderMoney . '元',
            'keyword2' => $orderItemsText,
            'keyword3' => $refundNo,
            'keyword4' => $rejectReasonText,
        ];

        $this->data = $data;
        return $this;
    }

    /**
     * 提现申请
     * @param $money
     * @param $to
     * @param $createDate
     * @return $this
     */
    public function withdrawApply($money, $to, $createDate)
    {
        $remark = '您的提现申请已提交我们将尽快审核您的提现，点击查看详细';

        $data = [
            'keyword1' => '￥' . $money . '元',
            'keyword2' => $to,
            'keyword3' => $createDate,
            'keyword4' => $remark,
        ];

        $this->data = $data;
        return $this;
    }

    /**
     * 提现成功
     * @param $money
     * @param $to
     * @param $createDate
     * @return $this
     */
    public function withdrawSuccess($money, $to, $createDate)
    {
        $remark = '您的提现已成功，已发放到您的<%s>，点击查看详细';
        $remark = sprintf($remark, $to);

        $data = [
            'keyword1' => '￥' . $money . '元',
            'keyword2' => $to,
            'keyword3' => $createDate,
            'keyword4' => $remark,
        ];

        $this->data = $data;
        return $this;
    }

    /**
     * 提现失败
     * @param $money
     * @param $to
     * @param $reason
     * @return $this
     */
    public function withdrawFail($money, $to, $reason)
    {
        $remark = '您的提现失败，点击查看详细';

        $data = [
            'keyword1' => '￥' . $money . '元',
            'keyword2' => $to,
            'keyword3' => $reason,
            'keyword4' => $remark,
        ];

        $this->data = $data;
        return $this;
    }
}