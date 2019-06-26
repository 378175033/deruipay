<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/26 0026
 * Time: 9:39
 */

namespace app\index\controller;
use think\Controller;
use think\Db;

class Pay extends Controller
{
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        if( !session( "?business") ){
            $this->redirect( url("Login/index") );
        }
    }

    public function index()
    {
        if( $this->request->isAjax() && $this->request->isPost() ){
            $money = $this->request->post("money",0);
            if( empty( $money ) ) $this->error( "请输入支付金额！");
            if( $money < 0 ) $this->error( "支付金额不合法！");
            $type = $this->request->post( "type", 0, 'intval');
            if( empty( $type ) ) $this->error( "请选择支付方式！");
            $business = session( 'business');
            $where = [
                'a.delete_time'   => 0,
                'a.status'      => 1,
                'a.id'            => $type,
                'b.business_id' => $business['id'],
            ];
            $passage = db::name('passageway')
                        ->alias('a')
                        ->join([
                            ['user_passageway b','a.id = b.passageway_id','left']
                        ])
                        ->where($where)
                        ->select();
            $str = [
                'business_id' => $business['id'],
                'order_id' => generate16Num(),
                'user_passageway_id' => $passage['0']['id'],
                'pay_from' => 1,
                'amount' => $money,
                'create_time' => time(),
                'status' => 3,
                'back_status' => 0,
            ];
            $order_add = Db('order')->insert($str);
            if (!$order_add) $this->error( "拉取支付失败");
            $data = [
                'title' => '测试支付1',
                'money' => $money
            ];
            switch ( $passage[0]['pay_type'] ){
                case 'alipay':
                    $api = new \app\manage\controller\Api();
                    $res = $api->Face( $data );
                    if( $res['code'] == 1 ){
                        $this->success( "获取二维码成功！", '', $res['data']);
                    } else{
                        $this->error( $res['msg'] );
                    }
                    break;
                case 'wechat':
                    break;
                case 'union':
                    $api = new \app\manage\controller\Pay();
                    $res = $api->pay( $data );
                    if( $res['code'] == 1 ){
                        $this->success( "获取二维码成功！", '', $res['data']);
                    } else{
                        $this->error( $res['msg'] );
                    }
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