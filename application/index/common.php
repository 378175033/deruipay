<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/20 0020
 * Time: 17:50
 */

function user_agent()
{
    $ua = $_SERVER['HTTP_USER_AGENT'];
    $type = "未识别扫码方式";
    if (strpos($ua, 'MicroMessenger')) {
        $type = "wechat";
    }
    elseif (strpos($ua, 'AlipayClient')) {
        $type = "alipay";
    }
    elseif (strpos($ua, 'QQ/')) {
        $type = 'qq';
    } else {
        $type = $ua;
    }
    return $type;
}