<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/14 0014
 * Time: 13:41
 */

namespace app\manage\model;
use think\Model;

/**
 * 用户模型
 * Class User
 * @package app\manage\model
 */
class User extends Model
{
    protected $autoWriteTimestamp = true;

    /**
     * 2019/6/14 0014 13:42
     * @desc 检测用户是否登录
     * @ApiParams
     * @ApiReturnParams
     */
    public function checkLogin()
    {
        if( session('?userInfo') ){
            return true;
        }
        return false;
    }

    /**
     * 2019/6/14 0014 16:00
     * @desc 用户登录信息校验
     * @ApiParams
     * @ApiReturnParams
     */
    public function doLogin( $data )
    {
        $username = $data['username'];
        $msg = msg();
        if( !empty( $username ) ){
            $where = [
                'username'  => $username
            ];
            $info = $this->where( $where )->find();
            if( $info ){
                if( $info['status'] == 1 ) {
                    if( compare_password( $info['password'],$data['password'], $info['salt'] ) ){
                        $msg['status'] = 1;
                        session( "userInfo", $info);
                        session( 'userAuth', $info['rule'] );
                    } else {
                        $msg['msg'] = "密码信息错误！";
                    }
                } else {
                    $msg['msg'] = "用户已被禁用，请前往联系客服申请启用！";
                }
            } else {
                $msg['msg'] = "用户不存在！";
            }
        } else {
            $msg['msg'] = "用户名不能为空！";
        }
        return $msg;
    }
}