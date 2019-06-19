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
//    public function getStatusAttr($value)
//    {
//        $status = [0=>'等待审核',1=>'禁用',2=>'正常',3=>'待审核'];
//        return $status[$value];
//    }

    public function getBusIdAttr($value)
    {
        $res = db('business')->where('id', $value)->value('name');
        return $res;
    }

    public static function addWithdrawLog($value)
    {
        if ($value['status'] == 2) {  //成功
            db('business')->where('id', $value['id'])->setInc('money', $value['money']);
            self::log_c($value);
        }
        if ($value['status'] == 1) {  //拒绝
            db('business')->where('id', $value['id'])->setDec('money', $value['money']);
            self::log_c($value);
        }
    }

    public static function log_c($val)
    {
        $data = [
            'bus_id' => $val['id'],
            'money' => $val['money'],
            'note' => $val['check_desc'],
            'status' => $val['status'],
            'create_time' => time()
        ];
        db('withdraw_log')->insert($data);
    }
}