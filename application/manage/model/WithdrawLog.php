<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/19 0019
 * Time: 15:48
 */

namespace app\manage\model;


use think\Model;

class WithdrawLog extends Model
{

    public function getBusIdAttr($val)
    {
        $res = db('business')->where('id',$val)->value('name');
        return $res;
    }
}