<?php
namespace app\manage\controller;
use think\Controller;
use daxiangpay\daxiangpay as api;

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

    public function notify()
    {
        
    }
}