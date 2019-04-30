<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/28
 * Time: 17:16
 */
return [
    'notify' => [
        'ADMIN_ORDER_PAY_SUCCESS' => [//新物流订单
            'msg' => "<div>您有<span style='color:#22c397;padding:0 2px;'>%s</span>笔新的<span style='color:#22c397;padding:0 2px;'>待发货物流订单</span>未处理</div>",//消息文本
            'redirectUri' => 'orderManagement'//跳转路径
        ],
        'ADMIN_ORDER_STORE_SUCCESS' => [//新到店订单
            'msg' => "<div>您有<span style='color:#22c397;padding:0 2px;'>%s</span>笔新的<span style='color:#22c397;padding:0 2px;'>未取货订单</span>未处理</div>",
            'redirectUri' => 'storeOrder'
        ],
        'ADMIN_ORDER_CITY_PAY_SUCCESS' => [//新同城订单
            'msg' => "<div>您有<span style='color:#22c397;padding:0 2px;'>%s</span>笔新的<span style='color:#22c397;padding:0 2px;'>同城订单</span>待接单</div>",
            'redirectUri' => 'cityOrder'
        ],
        'ADMIN_ORDER_CITY_CANCEL' => [//同城取消订单
            'msg' => "<div>订单号<<span style='color:#22c397;padding:0 2px;'>%s</span>>,买家已取消订单",
            'redirectUri' => ''
        ],
        'ADMIN_REFUND_APPLY' => [//买家退货申请
            'msg' => "<div>您有<span style='color:#22c397;padding:0 2px;'>%s</span>笔新的<span style='color:#22c397;padding:0 2px;'>退款申请</span>未处理</div>",
            'redirectUri' => 'saleManagement'
        ],
        'ADMIN_REFUND_GOODS_AGREE' => [//买家已退货-待退款
            'msg' => "<div>退单号<<span style='color:#22c397;padding:0 2px;'>%s</span>>,买家已完成退货",
            'redirectUri' => 'saleManagement'
        ]
    ]
];