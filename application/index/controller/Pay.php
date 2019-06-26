<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/26 0026
 * Time: 9:39
 */

namespace app\index\controller;
use think\Controller;
use app\manage\controller\PayModl;

class Pay extends Controller
{
    public function index()
    {
        if( $this->request->isAjax() && $this->request->isPost() ){
            $money = $this->request->post("money",0);
            if( empty( $money ) ) $this->error( "请输入支付金额！");
            if( $money < 0 ) $this->error( "支付金额不合法！");
            $type = $this->request->post( "type", 0, 'intval');
            if( empty( $type ) ) $this->error( "请选择支付方式！");
            $where = [
                'delete_time'   => 0,
                'status'        => 1,
                'id'            => $type
            ];
            $type = db( 'passageway')->where($where )->value("pay_type");
            switch ( $type ){
                case 'alipay':
                    $pay = new PayModl();
                    $data = [
                        'title' => '测试支付1',
                        'money' => $money
                    ];
                    new $pay->Face( $data );
                    break;
                case 'wechat':
                    break;
                case 'union':
                    break;
                default:
                    $this->error( "暂无该支付方式！请重新选取");
            }

        }
        //获取支付通道
        $where = [
            'delete_time'   => 0,
            'status'        => 1
        ];
        $way = db( "passageway")->field('id,name')->where( $where )->select();
        $this->assign( 'way', $way);
        return $this->fetch();
    }
}