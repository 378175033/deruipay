<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/14 0014
 * Time: 13:41
 */

namespace app\manage\model;
use think\Model;


class Order extends Model
{
    protected $autoWriteTimestamp = true;


    // status属性读取器
    protected function getStatusAttr($value)
    {
        $status = [0 => '审核中', 1 => '成功', 2 => '未提交', 3 => '已提交', 4 => '代付失败', 5 => '提交失败', 6 => '已退回'];
        return $status[$value];
    }


    // status属性读取器
    protected function getBackStatusAttr($value)
    {
        $status = [0 => '无回调', 1 => '成功', 2 => '失败'];
        return $status[$value];
    }
}