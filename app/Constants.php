<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/3
 * Time: 10:38
 */

namespace App;


class Constants
{

    const OS_TYPE = 'linux';

    const TUBE_ORDER = 'Order';//订单频道

    const TUBE_USER = 'User';//用户频道

    const TUBE_TRADER = 'Trader';//商家频道

    const TUBE_ACTIVITY = 'Activity';//活动频道

    //订单类型 微信小程序拼团订单
    const ORDER_TYPE_GROUP_PURCHASE = 20;
    //订单类型 秒杀订单
    const ORDER_TYPE_OLD_SECKILL = 32;
    //订单类型 满减活动订单
    const ORDER_TYPE_FULL_REDUCTION = 40;
    //订单类型 限时折扣活动订单
    const ORDER_TYPE_LIMIT_DISCOUNT = 41;
    //订单类型 砍价活动订单
    const ORDER_TYPE_BARGAIN = 42;
    //订单类型  混合订单类型  存在拆单情况
    const ORDER_TYPE_MIXED_ACTIVITY = 50;

    //取货方式
    const GET_PRODUCT_TYPE_DELIVERY = 1;//物流
    const GET_PRODUCT_TYPE_STORE = 2;//门店自提
    const GET_PRODUCT_TYPE_CITY = 3;//同城配送

    const LAYOUT_A = 101;//图文布局
    const LAYOUT_B = 102;//纯图片布局
    const LAYOUT_C = 103;//

    const TITLE_DELIVERY = '订单发货';

}