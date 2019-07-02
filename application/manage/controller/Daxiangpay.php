<?php

namespace app\manage\controller;

use think\Controller;
use daxiangpay\daxiangpay as api;
use think\Request;
use think\log;

class Daxiangpay extends controller
{
    public function testpay()
    {
        $data = ['amount' => '0.01', 'bankname' => '建设银行', 'bankcardid' => '6217003810031856423', 'bankfullname' => '胡译锋', 'bankidc' => '510902199301219536', 'bankmobile' => '15708432599', 'screen' => 1];
        $this->pay($data);
    }

    public function pay($data)
    {
        $api = new api();
        $api->pay($data);
    }

    public function notify(Request $request)
    {
        $api = new api();
        /**
        '/manage/daxiangpay/notify' => '',
        'amount' => '0.01',
        'merid' => '18086',
        'orderid' => '20190702174810142695',
        'paystate' => 'success',
        'paytypekey' => 'fast',
        'tradeid' => 'DS2019070217480961955',
        'sign' => 'd08ef88623fdda60f0d5caa59fee9b87',
        'attach' => '6Ieq5a6a5LmJ5pWw5o2u',
         */
        $paidInfo = $request->post();
        $attach = $paidInfo['attach'];
        if (strlen($attach) > 0) {
            $attach = base64_decode($attach);
        }
        unset($paidInfo['attach']);
        $paidInfo = $api->signChk($paidInfo);
        if ($paidInfo === false) {
            echo '验签失败';
        } elseif ($paidInfo['paystate'] == 'success') {//支付成功
            echo '_CC_PAY_STATE_SUCCESS_';

            $orderId = $paidInfo['orderid'];    //发起支付时发送过去的 订单号
            $amount = floatval($paidInfo['amount']);    //支付金额
            $tradeId = $paidInfo['tradeid'];    //支付平台的流水号

        } else {
            echo $paidInfo['paystate'];
            echo '非支付成功状态';
        }
    }
}