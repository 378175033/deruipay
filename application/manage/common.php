<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/14 0014
 * Time: 16:04
 */

use think\Db;

/**
 * 2019/6/14 0014 16:06
 * @desc 密码加密
 * @ApiParams
 * @ApiReturnParams
 * @param $password string 需要加密的密码
 * @param $salt string 盐
 * @return  string $pass 生成的加密密码
 */
function encode_password($password, $salt)
{
    $pass = md5(crypt($password, $salt));
    return $pass;
}

/**
 * 2019/6/6 0006 13:27
 * @desc 软删除 加入回收站
 * @ApiParams
 * @ApiReturnParams
 * @param $rid
 * @param $table
 * @param string $desc
 */
function into_recycle($rid, $table, $desc = "")
{
    $data = [
        'create_time' => time(),
        'table' => $table,
        'rid' => $rid,
        'desc' => $desc,
        'user_id' => session('userInfo')['id']
    ];
    model('Recycle')->data($data)->allowField(['create_time', 'table', 'rid', 'desc'])->isUpdate(false)->save();
}

/**
 * 2019/6/14 0014 16:08
 * @desc生成随机盐
 * @ApiParams
 * @ApiReturnParams
 */
function getSalt()
{
    $str = "2345678abcdefhijkmnpqrstuvwxyzABCDEFGHJKLMNPQRTUVWXY";
    $name = substr(str_shuffle($str), mt_rand(0, strlen($str) - 5), 4);
    return $name;
}

/**
 * 2019/6/14 0014 16:27
 * @desc生成唯一编号
 * @return string
 * @ApiParams
 * @ApiReturnParams
 */
function unique_id()
{
    $unique_id = date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    return $unique_id;
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
function compare_password($c_password, $password, $salt)
{
    $pass = md5(crypt($password, $salt));
    if ($pass == $c_password) {
        return true;
    }
    return false;
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
function msg($msg = '', $status = 0, $url = '')
{
    $return = [
        'status' => $status,
        'msg' => $msg,
        'url' => $url
    ];
    return $return;
}

/**
 * 2019/6/6 0006 13:28
 * @desc 获取IP的真实地址
 * @$ip 默认为空，IP地址
 */
function GetIpLookup()
{
//    $ip = '125.70.179.81';
    $ip = request()->ip();
    if (empty($ip)) {
        return '请输入IP地址';
    }
    $ak = "M7kfUpOtIIYGY4uS6M1KD13sw0y2yUyT";
    $url = "http://api.map.baidu.com/location/ip?ip=" . $ip . "&ak=" . $ak;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_REFERER, '百度地图referer');
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (iPhone; CPU iPhone OS 7_0 like Mac OS X; en-us) AppleWebKit/537.51.1 (KHTML, like Gecko) Version/7.0 Mobile/11A465 Safari/9537.53');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $resp = curl_exec($ch);
    curl_close($ch);
    $resp = json_decode($resp, true);
    if ($resp['status'] == 0) {
        return $resp['content']['address'];
    }
    return $resp['message'];
}

/**
 * 用户设备类型
 * @return string
 */
function clientOS()
{
    $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
    if (strpos($agent, 'windows nt')) {
        $platform = 'windows';
    } elseif (strpos($agent, 'macintosh')) {
        $platform = 'mac';
    } elseif (strpos($agent, 'ipod')) {
        $platform = 'ipod';
    } elseif (strpos($agent, 'ipad')) {
        $platform = 'ipad';
    } elseif (strpos($agent, 'iphone')) {
        $platform = 'iphone';
    } elseif (strpos($agent, 'android')) {
        $platform = 'android';
    } elseif (strpos($agent, 'unix')) {
        $platform = 'unix';
    } elseif (strpos($agent, 'linux')) {
        $platform = 'linux';
    } else {
        $platform = 'other';
    }
    return $platform;
}

/**
 *  记录操作日志
 * @param string $opera 操作信息
 */
function operaLog($opera = '')
{
    \app\manage\model\Log::addLog($opera);
}


function getBusName($table = '', $id = 0, $value = 'name')
{
    if (empty($value)) {
        return '请输入值';
    }
    if(empty($table)){
        $table = 'business';
    }
    $bus_name = db($table)->where('id', $id)->value($value);
    return $bus_name;
}

