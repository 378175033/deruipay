<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/17 0017
 * Time: 13:15
 */

namespace app\manage\model;
use think\Model;

class Menu extends Model
{
    protected $autoWriteTimestamp = true;
    protected $auto = ['controller'];
    protected function setControllerAttr( $value )
    {
        return ucfirst( $value );
    }
    public function treeMenu()
    {
        $where = [
            'delete_time' => 0,
            'status'    => 1,
            'parent_id' => 0,
        ];
        if( session( "userInfo")['id'] != 1){
            $where['id'] = ['in', session('userAuth')];
        }
        $order = "sort asc,id asc";
        $menu = $this->where( $where )->order( $order )->select();
        foreach ( $menu as $key => $val ){
            $where['parent_id'] = $val['id'];
            $menu[$key]['child'] = $this->where( $where )->order( $order )->select();
        }
        return $menu;
    }

}