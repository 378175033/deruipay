<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/18 0018
 * Time: 14:49
 */

namespace app\manage\model;
use think\Model;

class UserPassageway extends Model
{
    protected $autoWriteTimestamp = true;


    // status属性读取器
    protected function getStatusAttr($value)
    {
        $status = [0 => '不可用', 1 => '可用'];
        return $status[$value];
    }


    // status属性读取器
    protected function getPayTypeAttr($value)
    {
        $status = config('pay_type');
        if (!array_key_exists($value, $status)){
            return '其他';
        }
        return $status[$value];
    }
}