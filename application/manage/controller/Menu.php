<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/17 0017
 * Time: 13:14
 */

namespace app\manage\controller;
use app\common\controller\Manage;

class Menu extends Manage
{
    public  function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        $this->table = "Menu";
        $this->model = model( "Menu");
    }

    public function remove()
    {
        //检测是否有子菜单
        $id = $this->request->param( 'id', 0, 'intval');
        if( empty( $id ) ){
            $this->error( "参数信息错误！");
        }
        $where = [
            'parent_id' => $id
        ];
        $num = $this->model->where( $where )->count();
        if( $num > 0 ){
            $this->error( "该菜单包含子菜单，请先移除子菜单");
        }
        parent::remove(); // TODO: Change the autogenerated stub
    }
}