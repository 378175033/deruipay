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
    public  function getList( $where = [] )
    {
        $condition = [
            'delete_time' => 0,
            'status' => 1,
        ];
        $condition = array_merge( $condition, $where);
        $list = $this->append(['is_open'])->where( $condition )->select();
        $UserPassageway = new UserPassageway();
        foreach ($list as $value){
            $value['is_open'] = $UserPassageway->where('passageway_id',$value['id'])->value('status');
        }
        return $list;
    }
}