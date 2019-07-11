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
function encode_password( $password, $salt )
{
    $pass = md5( crypt( $password, $salt ) );
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
function into_recycle( $rid, $table, $desc = "")
{
    $data = [
        'create_time' => time(),
        'table'     => $table,
        'rid'    => $rid,
        'desc'  => $desc,
        'user_id'   => session('userInfo')['id']
    ];
    model('Recycle')->data( $data )->allowField(['create_time','table','rid','desc'])->isUpdate( false )->save();
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
    $name=substr(str_shuffle($str),mt_rand(0,strlen($str)-5),4);
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
    $unique_id = date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
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
function compare_password( $c_password,$password, $salt )
{
    $pass = md5( crypt( $password, $salt ) );
    if( $pass == $c_password ){
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
 *  记录操作日志
 * @param string $opera 操作信息
 */
function operaLog($opera = '')
{
    \app\manage\model\Log::addLog($opera);
}
