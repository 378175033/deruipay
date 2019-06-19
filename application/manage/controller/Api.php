<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/17 0017
 * Time: 14:46
 */

namespace app\manage\controller;
use think\Controller;

class Api extends Controller
{
    public  function getmenus()
    {
        if( request()->isAjax() && request()->isPost() )
        {
            $where = [
                'delete_time'   => 0
            ];
            $menu = model('Menu')->where( $where )->select();
            $return = array();
            foreach ( $menu as $key => $val ){
                $return[$key]['path'] = "/".$val['controller']."/".$val['action'];
                $return[$key]['component'] = "manage/".$val['controller']."/".$val['action'];
                $return[$key]['name'] = $val['name'];
            }
            $this->success( "成功获取菜单！",'', $return );
        }
    }

    public function getMenu( $current = 0 )
    {
        $where = [
            'delete_time'   => 0,
            'status'    => 1,
            'parent_id' => 0
        ];
        $list = model( 'menu')->where( $where )->field( "id,name"  )->select();
        $str = "";
        if( count( $list ) ){
            foreach ( $list as $v ){
                $cc = "";
                if( $v['id'] == $current ){
                    $cc = "selected";
                }
                $str .= "<option value='".$v['id']."' $cc>|--".$v['name']."</option>";
                $where['parent_id'] = $v['id'];
                $child = model( 'menu')->where( $where )->field( "id,name"  )->select();
                foreach ( $child as $val ){
                    $cc = "";
                    if( $val['id'] == $current ){
                        $cc = "selected";
                    }
                    $str .= "<option value='".$val['id']."' $cc>|--|--".$val['name']."</option>";
                }
            }
        }
        return $str;
    }

    /**
     * 2019/6/5 0005 16:52
     * @desc 获取权限配置菜单列表
     * @ApiParams id 用户组ID
     * @ApiReturnParams data tree结构的菜单列表
     */
    public function getAuth()
    {
        $id = request()->param( 'id', 0, 'intval');
        if( empty( $id ) ) {
            die("参数错误！");
        }
        $where = [
            'id' => $id,
            'status'    => 1
        ];
        $rule = model('user')->where( $where )->value('rule');
        $data = model('menu')->where(['delete_time'=>0])->field('id,parent_id pId,name')->select();
        foreach ( $data as $key => $v ){
            if( in_array( $v['id'], explode(',', $rule) ) ){
                $data[$key]['checked'] = true;
            }
        }
        $this->success( $data );
    }
}