<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

//下划线命名到驼峰命名
function toCamelCase($str)
{
    $array = explode('_', $str);
    $result = $array[0];
    $len=count($array);
    if($len>1)
    {
        for($i=1;$i<$len;$i++)
        {
            $result.= ucfirst($array[$i]);
        }
    }
    return $result;
}

/**
 * 2019/7/12 0012 14:22
 * @param $str
 * @return string
 * 驼峰转下划线
 */
function toUnderScore($str)
{
    $dstr = preg_replace_callback('/([A-Z]+)/',function($matchs) {
                return '_'.strtolower($matchs[0]);
            },$str);
    return trim(preg_replace('/_{2,}/','_',$dstr),'_');
}



function ismobile()
{
    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
        return true;
    //此条摘自TPM智能切换模板引擎，适合TPM开发
    if (isset ($_SERVER['HTTP_CLIENT']) && 'PhoneClient' == $_SERVER['HTTP_CLIENT'])
        return true;
    //如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if (isset ($_SERVER['HTTP_VIA']))
        //找不到为flase,否则为true
        return stristr($_SERVER['HTTP_VIA'], 'wap') ? true : false;
    //判断手机发送的客户端标志,兼容性有待提高
    if (isset ($_SERVER['HTTP_USER_AGENT'])) {
        $clientkeywords = array(
            'nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg', 'sharp', 'sie-', 'philips', 'panasonic', 'alcatel', 'lenovo', 'iphone', 'ipod', 'blackberry', 'meizu', 'android', 'netfront', 'symbian', 'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi', 'openwave', 'nexusone', 'cldc', 'midp', 'wap', 'mobile'
        );
        //从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
            return true;
        }
    }
    //协议法，因为有可能不准确，放到最后判断
    if (isset ($_SERVER['HTTP_ACCEPT'])) {
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
            return true;
        }
    }

    return false;
}

function generate16Num()
{
    $uid = date('Ymd') . str_pad(mt_rand(1, 99999), 8, mt_rand(1, 99999), STR_PAD_LEFT);
    return $uid;
}

/**
 * 2019/7/8 0008 10:43
 * 过期时间查询
 */
function getSetting($vlaue = 'close')
{
    $setting = DB('setting')->where('vkey', $vlaue)->find();
    return $setting['vvalue'];
}

function random($length = 6, $type = 'string', $convert = 0)
{
    $config = array(
        'number' => '1234567890',
        'letter' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
        'string' => 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789',
        'all' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'
    );

    if (!isset($config[$type]))
        $type = 'string';
    $string = $config[$type];

    $code = '';
    $strlen = strlen($string) - 1;
    for ($i = 0; $i < $length; $i++) {
        $code .= $string{mt_rand(0, $strlen)};
    }
    if (!empty($convert)) {
        $code = ($convert > 0) ? strtoupper($code) : strtolower($code);
    }
    return $code;
}

/**
 * 加密方法
 * @param string $str
 * @return string
 */
function encode($message, $encodingaeskey)
{
    $key = base64_decode($encodingaeskey . '=');
    $text = random(16) . pack("N", strlen($message)) . $message;
    $iv = substr($key, 0, 16);

    $block_size = 32;
    $text_length = strlen($text);
    $amount_to_pad = $block_size - ($text_length % $block_size);
    if ($amount_to_pad == 0) {
        $amount_to_pad = $block_size;
    }
    $pad_chr = chr($amount_to_pad);
    $tmp = '';
    for ($index = 0; $index < $amount_to_pad; $index++) {
        $tmp .= $pad_chr;
    }
    $text = $text . $tmp;

    $encrypted = openssl_encrypt($text, 'aes-256-ofb', $key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $iv);
    $encrypt_msg = base64_encode($encrypted);
    return $encrypt_msg;
}

function decode($message, $encodingaeskey = '')
{
    $key = base64_decode($encodingaeskey . '=');

    $ciphertext_dec = base64_decode($message);
    $iv = substr($key, 0, 16);

    $decrypted = openssl_decrypt($ciphertext_dec, 'aes-256-ofb', $key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $iv);
//return $decrypted;
    $pad = ord(substr($decrypted, -1));
    if ($pad < 1 || $pad > 32) {
        $pad = 0;
    }
    return $pad;
}

/**
 * @desc校验手机短信验证码
 * Created by PhpStorm
 * User: zhaolan
 * Date: 2019/7/11 0011 15:29
 * @param $code
 * @return bool
 */
function checkSms( $code )
{
    if( md5( $code ) != session("smsCode")  ){
        return false;
    } else {
        return true;
    }
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

/**
 * @desc
 * Created by PhpStorm
 * User: zhaolan
 * Date: 2019/7/15 0015 11:30
 * @param string $name
 * @return mixed
 */
function deploy( $name = '' )
{
    $data = session("deploy");
    if( empty( $name ) ) return $data;
    foreach ( $data as $v ){
        if( $v['vname'] == $name ){
            return $v['value'];
        }
    }
    return "";
}

/**
 * 2019/7/18 0018 12:51
 * @param $key
 * @param $secret
 * @param $timestamp
 * @return string
 * 生成签名
 */
function getSign($key,$timestamp,$secret){

    $data = [
        'api_key'=>$key,
        'api_secret'=>$secret,
        'timestamp'=>$timestamp,
    ];

    ksort($data);//先升序排序

    $data = http_build_query($data);//把数组转成http格式

    return md5($data);//加密

}