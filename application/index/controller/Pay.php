<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/26 0026
 * Time: 9:39
 */

namespace app\index\controller;
use app\common\controller\Business;
use think\Config;
use daxiangpay\daxiangpay as api;
use think\Db;
use think\Log;
use think\Request;
use think\Validate;

class Pay extends Business
{
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
    }

    public function index()
    {
        $business = $this->user;
        $request = $this->request;
        if( $request->isAjax() && $request->isPost() ){
            $money = $request->post("money",0);
            $type = $request->post( "type", 0, 'intval');

            $this->verify($request,$type);
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
            $outTradeNo = "zcss" . date('Ymdhis') . mt_rand(100, 1000);
            $str = [
                'out_trade_no' => $outTradeNo,
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
                'money' => $money,
                'out_trade_no'=>$outTradeNo,
            ];
            if($type == 10){
                $data = [
                    'amount' => $money,
                    'bankname' => $request->post('union'),
                    'bankcardid' => $request->post('bank_code'),
                    'bankfullname' => $request->post('name'),
                    'bankidc' => $request->post('idCard'),
                    'bankmobile' => $request->post('phone'),
                    'type' => $request->post('type'),
                    'screen' => 1
                ];
            }
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
                    $api = new api();
                    $api->pay($data);
                    break;
                case "free_wechat":
                    $data['type'] = "1";
                    $api = new Api();
                    $res = $api->free_pay( $data );
                    if( $res['code'] == 1 ){
                        $this->success( "二维码获取成功！",'', $res['data']);
                    } else {
                        $this->error( $res['msg'] );
                    }
                    break;
                case "free_alipay":
                    $data['type'] = "2";
                    $api = new Api();
                    $res = $api->free_pay( $data );
                    if( $res['code'] == 1 ){
                        $this->success( "二维码获取成功！",'', $res['data']);
                    } else {
                        $this->error( $res['msg'] );
                    }
                    break;
                default:
                    $this->error( "暂无该支付方式！请重新选取");
            }
        }
        //获取支付通道
        $where = [
            'p.delete_time'   => 0,
            'p.status'        => 1,
            'user_passageway.status'       => 1,
            'user_passageway.business_id'  => $business['id'],
        ];
        $way = db('passageway')
            ->field('p.id,p.name')
            ->alias('p')
            ->join('user_passageway','user_passageway.passageway_id = p.id')
            ->where($where)
            ->select();
        $this->assign( 'way', $way);
        return $this->fetch();
    }


    public function verify($request,$type){
        $rule = [
            'money'  => 'require|gt:0',
            'type'  => 'require|integer',
        ];
        $field = [
            'money'  => '支付金额',
            'type'  => '支付方式',
        ];
        if($type == 10){
            $rule['union'] = 'require';
            $rule['bank_code'] = 'require|min:12';
            $rule['name'] = 'require';
            $rule['mobile'] = 'require|min:11|max:11';
            $rule['idCard'] = 'require|min:15|max:18';

            $field['union'] = '银行名称';
            $field['bank_code'] = '银行卡号';
            $field['name'] = '开户人名称';
            $field['mobile'] = '银行预留手机号';
            $field['idCard'] = '身份证号码';
        }

        $validate = new Validate($rule, [] , $field);
        $result   = $validate->check($request->post());
        if(!$result){
            $this->error($validate->getError());
        }
        if($type == 10){
            if(!preg_match_all("/^1[34578]\d{9}$/", $request->post("mobile"), $mobiles)){
                $this->error('银行预留手机号规则错误！');
            }
            if(!preg_match('/^([1-9]{1})(\d{14}|\d{18})$/', $request->post("bank_code"),$match)){
                $this->error('银行卡号规则错误！');
            }
            if(!preg_match('/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/', $request->post("idCard"),$match)){
                $this->error('身份证号输入不合法！');
            }
        }
    }

    /**
     * 2019/6/28 0028 10:31
     * @param Request $request
     * 支付宝回调
     */
    public function aliPayNotify()
    {
        $api = new \app\manage\controller\Api();
        return $api->succNotifyServer();
    }

    public function freeList()
    {
        if( $this->request->isPost() && $this->request->isAjax() ){
            $id = $this->request->param( 'id',0,'intval');
            if( empty( $id ) ) $this->error( "参数错误！");
            $where = [ 'id'    => $id,'is_free'   => 0];
            $type = db( 'passageway')->where( $where)->value( 'pay_type');
            if( !$type ) $this->error( "该支付方式不存在或没有变化！");
            $business_id = $this->user['id'];
            $where = [
                'business_id'   => $business_id,
                'delete_time'   => 0,
                'status'        => 1,
                'passageway_id' => $id
            ];
            $n = db( 'user_passageway')->where( $where )->count();
            if( $n < 1 ) $this->error( "该商户未开启此通道！");
            switch( $type ){
                case "free_wechat":
                    $where = ['type'=>1];
                    break;
                case "free_alipay":
                    $where = ['type'=>2];
                    break;
                default:
                    $this->error( "未定义支付方式！");
                    break;
            }
            $list = db('qrcode')->where( $where )->select();
            $str = "<input type='radio' title='不定额测试' lay-filter='randM'  name='mt' checked value='0'>";
            foreach ( $list as $key => $val ){
                $str .= "<input type='radio' data-price='".$val['price']."' title='定额测试(&yen;".$val['price'].")' name='mt' lay-filter='randM' value='".$val['id']."'>";
            }
            $this->success( $str );
        }
        $this->error( "请求方式错误");
    }
}