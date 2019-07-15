<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/19 0019
 * Time: 10:27
 */

namespace app\manage\model;

use think\Model;

class Withdraw extends Model
{
    /**
     * 2019/6/27 0027 15:55
     * @desc
     * @ApiParams
     * @ApiReturnParams
     * @param $data
     */
    public function changeWithdraw( $data )
    {
        //是否审核通过
        $status = $data['status'];
        if( $status == 1 ){ //审核未通过
            //将余额返回账户
            $old_money = model('business')->where( 'id',$data['bus_id'])->value( 'money');
            model( "business")->changeMoney(  $data['money'], $old_money+$data['money'], $data['bus_id'], 4);
            //如果是扣除余额的方式 返回手续费
            if( $data['fee_type'] == 2){
                model( "business")->changeMoney(  $data['fee'], $old_money+$data['money']+$data['fee'], $data['bus_id'], 6);
            }
        }
    }
}