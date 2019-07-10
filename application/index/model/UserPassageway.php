<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/7/9 0009
 * Time: 18:48
 */
namespace app\index\model;
use think\Model;

class UserPassageway extends Model{


    public function in($id, $business)
    {
        $condition = [
            'delete_time' => 0,
            'status' => 1,
            'id' => $id,
            'business_id' => $business
        ];
        $res = $this->where( $condition )->find();
        return $res;
    }
}