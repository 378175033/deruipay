<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/27 0027
 * Time: 10:31
 */

namespace app\business\model;


use think\Model;

class AccountLog extends Model
{
    public function getInfoAttr($value)
    {
        $status = [0=>'订单录入',1=>'平台设置余额',2=>'平台设置冻结金额',3=>'申请提现',4=>'申请提现返还',5=>"提现手续费",6=>"提现手续费返回"];
        return $status[$value];
    }
}