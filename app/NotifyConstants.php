<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/16
 * Time: 11:57
 */

namespace App;


class NotifyConstants
{

    const USER_ORDER_NO_PAY = 'USER_ORDER_NO_PAY';//未支付订单 通知会员

    const USER_ORDER_PAY_SUCCESS = 'USER_ORDER_PAY_SUCCESS';//新物流订单 通知会员

    const ADMIN_ORDER_PAY_SUCCESS = 'ADMIN_ORDER_PAY_SUCCESS';//新物流订单 通知商家

    const ADMIN_ORDER_STORE_SUCCESS = 'ADMIN_ORDER_STORE_SUCCESS';//新到店订单 通知商家

    const ADMIN_ORDER_CITY_PAY_SUCCESS = 'ADMIN_ORDER_CITY_PAY_SUCCESS';//新同城订单 通知商家

    const USER_ORDER_TRANSSHIP = 'USER_ORDER_TRANSSHIP';//物流订单 商家发货

}