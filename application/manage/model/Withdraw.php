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

    public function getBusIdAttr($value)
    {
        $res = db('business')->where('id', $value)->value('name');
        return $res;
    }

    public static function addWithdrawLog($value)
    {
        if ($value['status'] == 2) {  //成功
            model('business')->where('id', $value['id'])->setInc('money', (int)$value['money']);
            self::log_c($value);
            self::addAccountLog($value);

        }
        if ($value['status'] == 1) {  //拒绝
//            db('business')->where('id', $value['id'])->setDec('money', $value['money']);
            self::log_c($value);
        }
    }

    public static function addAccountLog($value)
    {
        $insert = [
            'bus_id'    => $value['id'],
            'account'   => $value['money'],
            'desc'      => $value['check_desc'],
            'info'      => 3
        ];
        model( 'account_log')->add( $insert );
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