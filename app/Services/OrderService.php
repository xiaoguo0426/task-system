<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/16
 * Time: 15:21
 */

namespace App\Services;


use App\Models\Order;

class OrderService
{

    /**
     * 订单消息显示文本【第一个商品】
     */
    public function getMsgOrderText($siteID, $userID, $orderID)
    {

        $order = Order::where('OrderID', $orderID)
            ->where('SiteID', $siteID)
            ->where('UserID', $userID)
            ->where()
            ->find();

        //需要判断订单是否有拆单情况

    }

}