<?php
namespace app\manage\controller;
use think\Controller;
use zhangv\unionpay\UnionPay;

class Pay extends controller
{
    public function testPay()
    {
        $this->pay(['accNo'=>'6216261000000000018','money'=>100], ['smsCode' => '111111']);
    }

    public function pay( $data, array $customerInfo)
    {
        $accNo = $data['accNo'];
        if (!is_string($accNo)) {
            $this->error('账户类型错误！');
        }
        list($mode,$config) = config('union_pay');
        $unionPay = UnionPay::Direct($config,$mode);

        $payOrderNo = date('YmdHis');
        $amt = $data['money'];

        $res = $unionPay->pay($payOrderNo,$amt,$accNo,$customerInfo);
        $this->afterPay($res);
    }

    public function afterPay($res)
    {
        dump($res);
    }

    public function payreturn()
    {
        list($mode,$config) = config('union_pay');
        $unionPay = UnionPay::B2C($config,$mode);

        $postdata = $_REQUEST;
        $unionPay->onPayNotify($postdata,function($notifydata){
            echo 'SUCCESS';
            var_dump($notifydata);
        });
    }

    public function paynotify()
    {
        list($mode,$config) = config('union_pay');
        $unionPay = UnionPay::B2C($config,$mode);

        $notifyData = $_POST;
        $respCode = $notifyData['respCode'];
        if($respCode == '00'){
            $unionPay->onNotify($notifyData,'demoCallback');
        }elseif(in_array($respCode,['03','04','05'])){
            //后续需发起交易状态查询交易确定交易状态
        }else{
            echo 'fail';
        }
    }
}