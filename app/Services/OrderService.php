<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/16
 * Time: 15:21
 */

namespace App\Services;


use App\Constants;
use App\Models\Order;
use App\Models\OrderItem;

class OrderService
{
    public $orderInfo;

    public $orderImage;

//    public function __construct($siteID, $userID, $orderID)
//    {
//
//    }

    /**
     * 订单消息显示文本【第一个商品】
     */
    public function getMsgOrderText($siteID, $userID, $orderID)
    {

        $orderInfo = $this->getOrderInfo($siteID, $userID, $orderID);

        if ($orderInfo->isEmpty()) {
            return '';
        }

        //需要判断订单是否有拆单情况

        if (Constants::ORDER_TYPE_MIXED_ACTIVITY == $orderInfo->orderType) {

            //查询第一条子单，再查询tbl_orderitem
            $subOrderInfo = Order::where('SiteID', $siteID)
                ->where('UserID', $userID)
                ->where('Pid', $orderID)
                ->field('OrderID as orderID')
                ->find();

            if ($subOrderInfo->isEmpty()) {
                return '';
            }

            $orderItemInfo = OrderItem::where('OrderID', $subOrderInfo->orderID)->field('ProductName as productName,Image as image')->find();

        } else {

            $orderItemInfo = OrderItem::where('OrderID', $orderID)->field('ProductName as productName, Image as image')->find();

        }

        if ($orderItemInfo->isEmpty()) {
            return '';
        }

        $orderImage = $orderItemInfo->image;

        $this->orderImage = $orderImage;

        return $orderItemInfo->productName;

    }


    public function getOrderInfo($siteID, $userID, $orderID)
    {

        if (empty($this->orderInfo)) {
            $where = [
                'SiteID' => $siteID,
                'UserID' => $userID,
                'OrderID' => $orderID
            ];
            $fields = 'OrderID as orderID,UserID as userID,Pid as pid,Contact as contact,Address as address,Mobile as mobile,GetProductType as getProductType,OrderType as orderType,DistributionType as distributionType,InvoiceNo as invoiceNo,LogisticsName as logisticsName,Money as money,FreightMoney as freightMoney,CrTime as crTime,Remark as remark';

            $orderInfo = $this->_getOrderInfo($where, $fields);
            $this->orderInfo = $orderInfo;
        }

        return $this->orderInfo;
    }

    private function _getOrderInfo($where, $fields = '*')
    {
        return Order::where($where)
            ->field($fields)
            ->find();
    }

}