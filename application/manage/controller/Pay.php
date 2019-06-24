<?php
namespace app\manage\controller;
use app\common\controller\Manage;
use zhangv\unionpay\UnionPay;

class Pay extends Manage
{

    public function _initialize()
    {
        parent::_initialize();
    }


    public function pay()
    {
        list($mode,$config) = config('union_pay');
//        halt($config);
        $unionPay = UnionPay::B2C($config,$mode);

        $payOrderNo = date('YmdHis');
        $amt = 1;

        $html = $unionPay->pay($payOrderNo,$amt);
        echo $html;
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