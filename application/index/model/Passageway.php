<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/7/9 0009
 * Time: 18:01
 */

namespace app\index\model;

use think\Model;

class Passageway extends Model
{
    public  function getList($business_id, $where = [])
    {
        $condition = [
            'delete_time' => 0,
            'status' => 1,
        ];
        $condition = array_merge( $condition, $where);
        $list = $this->append(['is_open'])->where( $condition )->select();
        $UserPassageway = new UserPassageway();
        foreach ($list as $value){
            $user_passageway = $UserPassageway->where('passageway_id',$value['id'])
                ->where('business_id',$business_id)
                ->find();
            $is_open = 1;
            if(!$user_passageway || !$user_passageway['status']){
                $is_open = 0;
            }
            $value['is_open'] = $is_open;
        }
        return $list;
    }
}