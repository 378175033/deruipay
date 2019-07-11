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

/**
 * 2019/6/14 0014 16:27
 * @desc 提示信息
 * @ApiParams
 * @ApiReturnParams
 * @param $msg string 信息
 * @param int $status 状态 0 =》 失败 1=》成功
 * @param string $url 跳转路径
 * @return array 提示信息数组
 */
function msg( $msg = '', $status = 0, $url = '')
{
    $return = [
        'status' => $status,
        'msg'   => $msg,
        'url'   => $url
    ];
    return $return;
}

/**
 * 2019/6/14 0014 16:21
 * @desc 密码验证！
 * @ApiParams
 * @ApiReturnParams
 * @param $c_password string 用户存储密码
 * @param $password string 用户登录密码
 * @param $salt string 用户盐密码
 * @return bool 密码是否正确
 */
function compare_password( $c_password,$password, $salt )
{
    $pass = md5( crypt( $password, $salt ) );
    if( $pass == $c_password ){
        return true;
    }
    return false;
}

/**
 * 2019/6/6 0006 13:28
 * @desc 获取IP的真实地址
 * @$ip 默认为空，IP地址
 */
function GetIpLookup(){
//    $ip = '125.70.179.81';
    $ip = request()->ip();
    if(empty($ip)){
        return '请输入IP地址';
    }
    $ak = "M7kfUpOtIIYGY4uS6M1KD13sw0y2yUyT";
    $url = "http://api.map.baidu.com/location/ip?ip=".$ip."&ak=".$ak;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_REFERER, '百度地图referer');
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (iPhone; CPU iPhone OS 7_0 like Mac OS X; en-us) AppleWebKit/537.51.1 (KHTML, like Gecko) Version/7.0 Mobile/11A465 Safari/9537.53');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $resp = curl_exec($ch);
    curl_close($ch);
    $resp = json_decode( $resp, true);
    if( $resp['status'] == 0 ){
        return $resp['content']['address'];
    }
    return $resp['message'];
}
/**
 * 用户设备类型
 * @return string
 */
function clientOS() {
    $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
    if(strpos($agent, 'windows nt')) {
        $platform = 'windows';
    } elseif(strpos($agent, 'macintosh')) {
        $platform = 'mac';
    } elseif(strpos($agent, 'ipod')) {
        $platform = 'ipod';
    } elseif(strpos($agent, 'ipad')) {
        $platform = 'ipad';
    } elseif(strpos($agent, 'iphone')) {
        $platform = 'iphone';
    } elseif (strpos($agent, 'android')) {
        $platform = 'android';
    } elseif(strpos($agent, 'unix')) {
        $platform = 'unix';
    } elseif(strpos($agent, 'linux')) {
        $platform = 'linux';
    } else {
        $platform = 'other';
    }
    return $platform;
}