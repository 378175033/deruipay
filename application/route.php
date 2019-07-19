<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
//\think\Route::post('sign','index/Pay/verifySign');
//创建订单
\think\Route::post('create_order','index/Pay/createOrder');
\think\Route::post('get_pay','index/Pay/getPay');

return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],
    'manage' => 'manage/index/index',
    'sms' => 'index/sms/code',

//    'pay'=>'index/pay/index',//支付页面
    'status'=>'index/pay/orderStatus',//支付页面
    'appHeart' => 'index/FreeApi/appHeart',//心跳监控接口
    'appPush'  => 'index/FreeApi/appPush',//App推送付款数据接口
    'return'  => 'index/FreeApi/returnInfo',//支付同步回调接口
    'notify'  => 'index/FreeApi/notify',//支付异步回调接口
    'createOrder'  => 'index/FreeApi/createOrder',//订单生成接口
];