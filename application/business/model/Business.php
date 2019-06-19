<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/14 0014
 * Time: 13:41
 */

namespace app\business\model;
use think\Model;

/**
 * 用户模型
 * Class User
 * @package app\manage\model
 */
class Business extends Model
{
    protected $autoWriteTimestamp = true;

    protected function getLastLoginTimeAttr( $value )
    {
        return date( 'Y-m-d H:i:s', $value);
    }
    /**
     * 2019/6/14 0014 13:42
     * @desc 检测用户是否登录
     * @ApiParams
     * @ApiReturnParams
     */
    public function checkLogin()
    {
        if( session('?business') ){
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
                'login_name'  => $username
            ];
            $info = $this->where( $where )->find();
            if( $info ){
                if( $info['check'] == 0 ){
                    $msg['msg'] = "用户信息审核中...请耐心等待！";
                } elseif( $info['check'] == 1){
                    $msg['msg'] = "用户信息审核未通过！";
                } else {
                    if( $info['status'] == 1 ) {
                        if( compare_password( $info['password'],$data['password'], $info['salt'] ) ){
                            $msg['status'] = 1;
                            $login = [
                                'last_login_time'   => time(),
                                'last_login_ip'     => request()->ip()
                            ];
                            $this->where( ['id'=>$info['id']] )->update( $login );
                            session( "business", $info);
                        } else {
                            $msg['msg'] = "密码信息错误！";
                        }
                    } else {
                        $msg['msg'] = "用户已被禁用，请前往联系客服申请启用！";
                    }
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