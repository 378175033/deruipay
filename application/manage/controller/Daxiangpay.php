<?php
namespace app\manage\controller;
use think\Controller;
use daxiangpay\daxiangpay as api;
use think\Request;
use think\log;
use think\Db;
use think\Exception;
use think\Loader;
use think\Validate;

class Daxiangpay extends controller
{

    public function index(){

        if($this->request->isAjax() && $this->request->isPost()){
            $request = $this->request;
            $rule = [
                'money'  => 'require|gt:0',
                'type'  => 'require|integer',
                'union'  => 'require',
                'bank_code'  => 'require|min:12',
                'name'  => 'require',
                'phone'   => 'require|min:11|max:11',
                'idCard'   => 'require|min:15|max:18',
            ];

            $field = [
                'money'  => '支付金额',
                'type'  => '支付方式',
                'union'  => '银行名称',
                'bank_code'  => '银行卡号',
                'name'  => '开户人名称',
                'phone'   => '银行预留手机号',
                'idCard'   => '身份证号码',
            ];

            $validate = new Validate($rule, [] , $field);
            $result   = $validate->check($this->request->post());
            if(!$result){
                $this->error($validate->getError());
            }

            if(!preg_match_all("/^1[34578]\d{9}$/", $request->post("phone"), $mobiles)){
                $this->error('银行预留手机号规则错误！');
            }
            if(!preg_match('/^([1-9]{1})(\d{14}|\d{18})$/', $request->post("bank_code"),$match)){
                $this->error('银行卡号规则错误！');
            }
            if(!preg_match('/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/', $request->post("idCard"),$match)){
                $this->error('身份证号输入不合法！');
            }
            $data = [
                'amount' => $request->post('money'),
                'bankname' => $request->post('union'),
                'bankcardid' => $request->post('bank_code'),
                'bankfullname' => $request->post('name'),
                'bankidc' => $request->post('idCard'),
                'bankmobile' => $request->post('phone'),
                'type' => $request->post('type'),
                'screen' => 1
            ];
            try{
                Db::startTrans();
                $this->addOrder($data);//往订单写入数据
                $api = new api();
                $api->pay($data);
                Db::commit();
            }catch (Exception $e){
                $this->error($e->getMessage());
                Db::rollback();
            }

        }
        return $this->fetch();
    }


    public function addOrder($data){
        //$business = $this->user;
        $str = [
            //'out_trade_no' => $outTradeNo,
            'business_id' => 1,
            'order_id' => generate16Num(),
//            'user_passageway_id' => $passage['0']['id'],
            'user_passageway_id' => 10,
            'pay_from' => 1,
            'amount' => $data['amount'],
            'create_time' => time(),
            'status' => 3,
            'back_status' => 0,
        ];
        $order_add = Db('order')->insert($str);
        if(!$order_add){
            $this->error('写入订单参数失败');
        }
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
            $order = db('order')->where(['out_trade_no' => $orderId])->find();
            if (!$order) {
                Log::error('order not exists');
                return 'order not exists';
            }
            $transaction_id =$tradeId;
            $order['batch'] = $transaction_id;// 支付宝交易号（流水号）
            $order['amount'] = $amount;
            $order['update_time'] = time();
            $order['back_time'] = time();
            $order['status'] = 1;//支付状态
            $order['back_status'] = 1;//回调状态
            $order['back_info'] = json_encode($paidInfo,true);//回调参数

            //修改订单信息
            db('order')->where(['order_id' => $orderId])->update($order);
            //支付成功的逻辑
            $this->accountLog($order);

        } else {
            echo $paidInfo['paystate'];
            echo '非支付成功状态';
        }
    }

    public function callbacknotify(Request $request)
    {
        Log::record($request);
    }

    /**
     * 2019/6/28 0028 16:52
     * @param $order
     * 回调成功的时候金额变动
     */
    public function accountLog($order){

        $accountLog = db('account_log')->where('bus_id',$order['business_id'])->order('id desc')->find();
        $Business = new \app\manage\model\Business();
        $Business->changeMoney($order['amount'],$accountLog['now_account']+$order['amount'],$order['business_id'],0);
    }
}