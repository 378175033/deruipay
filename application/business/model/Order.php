<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/24 0024
 * Time: 17:52
 */

namespace app\business\model;
use think\Model;

class Order extends Model
{
// status属性读取器
    protected function getStatusAttr($value)
    {
        $status = [0 => '审核中', 1 => '成功', 2 => '未提交', 3 => '已提交', 4 => '代付失败', 5 => '提交失败', 6 => '已退回'];
        return $status[$value];
    }
}