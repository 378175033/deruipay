<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/24 0024
 * Time: 17:52
 */

namespace app\business\model;
use think\Db;
use think\Model;

class Order extends Model
{
// status属性读取器
    protected function getStatusAttr($value)
    {
        $status = [0 => '审核中', 1 => '成功', 2 => '未提交', 3 => '已提交', 4 => '代付失败', 5 => '提交失败', 6 => '已退回'];
        return $status[$value];
    }

    protected function getPayFromAttr($value)
    {
        $status = [0 => '商户', 1 => '用户'];
        return $status[$value];
    }


    protected function getBackStatusAttr($value)
    {
        $status = [0 => '无回调', 1 => '成功', 2 => '失败'];
        return $status[$value];
    }

    public function getUserPassagewayIdAttr($value){

        $userPassageway = Db::name('user_passageway')->where('id',$value)->find();
        if($userPassageway){
            $passageway = Db::name('passageway')->find($userPassageway['passageway_id']);
            return $passageway['name'];
        }
        return $value;
    }


    public function getBusinessIdAttr($value){
        $business = Db::name('business')->field(['id','name'])->find($value);
        if($business){
            return $business['name'];
        }
        return $value;
    }


    public function getBackTimeAttr($value){
        if($value){
            $value = date('Y-m-d H:i:s',$value);
            return $value;
       }

       return null;

    }
}