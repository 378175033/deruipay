<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/20 0020
 * Time: 17:43
 */

namespace app\index\controller;
use think\Controller;

class Api extends Controller
{
    public function index()
    {
        //获取商户ID
        $id = request()->param( 'id', 0, 'intval');
        if( empty( $id ) ){
            $this->error( "参数信息错误！");
        }
        //获取扫码方式
        $type = user_agent();
        echo "<h1>这是ID为".$id."的商户来自".$type."的扫码</h1>";
    }

    public function unionpay()
    {

    }
}
