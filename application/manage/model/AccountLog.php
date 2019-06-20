<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/20 0020
 * Time: 13:10
 */

namespace app\manage\model;
use think\Model;

class AccountLog extends Model
{
    /**
     * 2019/6/20 0020 13:12
     * @desc添加数据
     * @ApiParams
     * @ApiReturnParams
     */
    public function add( $data )
    {
        $new = [
            'create_time'   => time(),
            'user_id'       => session( 'userInfo')['id']
        ];
        $data = array_merge( $new, $data);
        $this->allowField( true )->isUpdate( false )->save( $data );
    }

    public function getInfoAttr($value)
    {
        $status = [0=>'订单录入',1=>'平台设置余额',2=>'平台设置冻结金额'];
        return $status[$value];
    }
}