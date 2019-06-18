<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/6 0006
 * Time: 13:12
 */

namespace app\business\model;
use think\Model;

class LoginLog extends Model
{
    protected $autoWriteTimestamp = true;
    public function addLog()
    {
        if( session('business') ){
            $data = [
                'create_time' => time(),
                'ip'    => request()->ip(),//获取IP
                'address' => GetIpLookup(),//获取地址
                'device'  => clientOS(),//获取登录系统
                'user_id'   => session( 'userInfo')['id'],
                'username'   => session( 'userInfo')['nickname'],
                'type'  => 1
            ];
            $this->data( $data )->isUpdate( false )->save();
        }
        return '请先登录！';
    }
}