<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/7/2 0002
 * Time: 11:14
 */

namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Log;
use think\Validate;

class FreeApi extends Controller
{
    public $payId;//商户订单号
    public $type;//支付方式
    public $price;//支付金额
    public $sign;//签名
    public $is_Html;//是否返回html
    public $param;//额外参数
    /**
     * @desc 校验APP端心跳检测
     * Created by PhpStorm
     * User: zhaolan
     * Date: 2019/7/2 0002 11:16
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     * @return \think\response\Json
     */
    public function appHeart(){
        $this->closeEndOrder();
        $key = getSetting('key');
        $t = input("t");
        $_sign = $t.$key;
        if (md5($_sign)!=input("sign")){
            return json($this->getReturn(-1, "签名校验不通过"));
        }
        $jg = time()*1000 - $t;
        if ($jg>50000 || $jg<-50000){
            return json($this->getReturn(-1, "客户端时间错误"));
        }
        Db::name("setting")->where("vkey","lastheart")->update(array("vvalue"=>time()));
        Db::name("setting")->where("vkey","jkstate")->update(array("vvalue"=>1));
        return json($this->getReturn());
    }

    /**
     * @desc App推送付款数据接口
     * Created by PhpStorm
     * User: zhaolan
     * Date: 2019/7/2 0002 11:33
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     * @return \think\response\Json
     */
    public function appPush()
    {
        Log::info($this->request->param());
        $this->closeEndOrder();
        $key = getSetting('key');
        $t = input("t");
        $type = input("type");
        $price = input("price");
        $_sign = $type.$price.$t.$key;
        if (md5($_sign)!=input("sign")){
            return json($this->getReturn(-1, "签名校验不通过"));
        }
        $jg = time()*1000 - $t;
        if ($jg>50000 || $jg<-50000){
            return json($this->getReturn(-1, "客户端时间错误"));
        }
        Db::name("setting")
            ->where("vkey","lastpay")
            ->update(
                array(
                    "vvalue"=>time()
                )
            );
        $res = Db::name("pay_order")
            ->where("really_price",$price)
            ->where("state",0)
            ->where("type",$type)
            ->find();
        if ($res){
            $this->orders($type,$price);//回调订单操作
            Db::name("tmp_price")
                ->where("oid",$res['order_id'])
                ->delete();
            Db::name("pay_order")->where("id",$res['id'])->update(array("state"=>1,"pay_date"=>time(),"close_date"=>time()));

            $url = $res['notify_url'];

            $p = "payId=".$res['pay_id']."&param=".$res['param']."&type=".$res['type']."&price=".$res['price']."&reallyPrice=".$res['really_price'];

            $sign = $res['pay_id'].$res['param'].$res['type'].$res['price'].$res['really_price'].$key;
            $p = $p . "&sign=".md5($sign);

            if (strpos($url,"?")===false){
                $url = $url."?".$p;
            }else{
                $url = $url."&".$p;
            }
            $re = $this->getCurl($url);
            if ($re=="success"){

                return json($this->getReturn());
            }else{
                Db::name("pay_order")->where("id",$res['id'])->update(array("state"=>2));

                return json($this->getReturn(-1,"异步通知失败"));
            }
        }else{
            $data = array(
                "close_date" => 0,
                "create_date" => time(),
                "is_auto" => 0,
                "notify_url" => "",
                "order_id" => "无订单转账",
                "param" => "无订单转账",
                "pay_date" => 0,
                "pay_id" => "无订单转账",
                "pay_url" => "",
                "price" => $price,
                "really_price" => $price,
                "return_url" => "",
                "state" => 1,
                "type" => $type
            );
            Db::name("pay_order")->insert($data);
            return json($this->getReturn());

        }
    }

    /**
     * 2019/7/5 0005 13:13
     * @param $type
     * @param $price
     * 免签支付回调处理
     */
    public function orders($type,$price){
        $tmpPrice = Db::name('tmp_price')->where('price',$price*"100".'-'.$type)->find();
        if(!$tmpPrice){
            $this->error('查无数据');
        }
        $order = db('order')
            ->where('status',3)
            ->where('order_id',$tmpPrice['oid'])
            ->find();
        if(!$order){
            $this->error('查无订单信息');
        }
        $data['update_time'] = time();
        $data['back_time'] =  time();
        $data['status'] = 1;//支付状态
        $data['back_status'] = 1;//回调状态
        db('order')
            ->where('status',3)
           ->where('order_id',$tmpPrice['oid'])
            ->update($data);
        $api = new \app\manage\controller\Api();
        $api->accountLog($order);


    }

    /**
     * @desc 关闭过期订单接口(请用定时器至少1分钟调用一次)
     * Created by PhpStorm
     * User: zhaolan
     * Date: 2019/7/2 0002 11:18
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     * @return \think\response\Json
     */
    public function closeEndOrder(){

        $lastheart =getSetting('lastheart');
        if ((time()-$lastheart)>60){
            Db::name("setting")->where("vkey","jkstate")->update(array("vvalue"=>0));
        }

        $time = getSetting('close');

        $closeTime = time()-60*$time;
        $close_date = time();
        $res = Db::name("pay_order")
            ->where("create_date <=".$closeTime)
            ->where("state",0)
            ->update(array("state"=>-1,"close_date"=>$close_date));
        if ($res){
            $rows = Db::name("pay_order")->where("close_date",$close_date)->select();
            foreach ($rows as $row){
                Db::name("tmp_price")
                    ->where("oid",$row['order_id'])
                    ->delete();
            }
            $rows = Db::name("tmp_price")->select();
            foreach ($rows as $row){
                $re = Db::name("pay_order")->where("order_id",$row['oid'])->find();
                if ($re){

                }else{
                    Db::name("tmp_price")
                        ->where("oid",$row['oid'])
                        ->delete();
                }
            }
            return json($this->getReturn(1,"成功清理".$res."条订单"));
        }else{
            return json($this->getReturn(1,"没有等待清理的订单"));
        }
    }

    /**
     * 2019/7/5 0005 13:46
     * @param $passage
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     * 先把超过时间未支付成功的订单状态修改
     */
    public function closeOrder($passage)
    {
        $orders = Db('order')
            ->where('user_passageway_id', $passage[0]['id'])
            ->where('status',3)
            ->select();
        $time = getSetting('close');
        foreach ($orders as $order){
            if(time()-$order['create_time']>$time*60){
                Db('order')->where('user_passageway_id', $passage[0]['id'])->update(['status'=>5]);
            }
        }
    }

    /**
     * @desc 定义返回数据格式
     * Created by PhpStorm
     * User: zhaolan
     * Date: 2019/7/2 0002 11:38
     * @param int $code
     * @param string $msg
     * @param null $data
     * @return array
     */
    public function getReturn($code = 1, $msg = "成功", $data = null)
    {
        return array("code" => $code, "msg" => $msg, "data" => $data);
    }

    /**
     * @desc 创建订单
     * Created by PhpStorm
     * User: zhaolan
     * Date: 2019/7/2 0002 14:50
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     * @return \think\response\Json
     */
    public function createOrder($param)
    {
        $this->closeEndOrder();
        $this->checkParam();
        $key = getSetting('key');
        $notify_url = getSetting('notifyUrl');
        $return_url = getSetting('returnUrl');
        $_sign = md5($this->payId . $this->param . $this->type . $this->price . $key);
        Log::info('签名2');
        Log::info($this->payId .'-'. $this->param .'-'. $this->type .'-'. $this->price .'-'. $key);
//        $_sign = $this->payId . $this->param . $this->type . $this->price . $key;
        if ($this->sign != $_sign) {
            $this->error( "签名错误！");
        }
        $jkstate = getSetting('jkstate');
        if ($jkstate!="1"){
            $this->error( "监控端状态异常，请检查！");
        }
        $reallyPrice = bcmul($this->price ,100);
        $payQf = getSetting('payQf');
        $orderId = date("YmdHms") . rand(1, 9) . rand(1, 9) . rand(1, 9) . rand(1, 9);
        $ok = false;
        for ($i = 0; $i < 10; $i++) {
            $tmpPrice = $reallyPrice . "-" . $this->type;
            $row = Db::name( "tmp_price")->insert( [ "price"=> $tmpPrice, "oid" => $param['out_trade_no']]);
            if ($row) {
                $ok = true;
                break;
            }
            if ($payQf == 1) {
                $reallyPrice++;
            } else if ($payQf == 2) {
                $reallyPrice--;
            }
        }
        if (!$ok) {
            $this->error( "订单超出负荷，请稍后重试");
        }
        $reallyPrice = bcdiv($reallyPrice, 100,2);
        if ($this->type == 1) {
            $payUrl = getSetting('wxpay');

        } else if ($this->type == 2) {
            $payUrl = getSetting('zfbpay');
        }
        if ($payUrl == "") {
            $this->error( "请您先进入后台配置程序");
        }
        $isAuto = 1;
        $_payUrl = Db::name("qrcode")
            ->where("price", $reallyPrice)
            ->where("type", $this->type)
            ->find();
        if ($_payUrl) {
            $payUrl = $_payUrl['pay_url'];
            $isAuto = 0;
        }
        $res = Db::name("pay_order")->where("pay_id", $this->payId)->find();
        if ($res) {
            $this->error( "商户订单号已存在");
        }
        $createDate = time();
        $data = array(
            "close_date" => 0,
            "create_date" => $createDate,
            "is_auto" => $isAuto,
            "notify_url" => $notify_url,
            "order_id" => $param['out_trade_no'],
            "param" => $this->param,
            "pay_date" => 0,
            "pay_id" => $this->payId,
            "pay_url" => $payUrl,
            "price" => $this->price,
            "really_price" => $reallyPrice,
            "return_url" => $return_url,
            "state" => 0,
            "type" => $this->type
        );
        Db::name("pay_order")->insert($data);
        if ($this->is_Html == 1) {
            return $this->fetch();
//            echo "<script>window.location.href = 'payPage/pay.html?orderId=" . $orderId . "'</script>";
        } else {
            $data = array(
                "payId" => $this->payId,
                "orderId" => $param['out_trade_no'],
                "payType" => $this->type,
                "price" => $this->price,
                "reallyPrice" => $reallyPrice,
                "payUrl" => $payUrl,
                "isAuto" => $isAuto,
                "state" => 0,
                "timeOut" => getSetting(),//过期时间
                "date" => $createDate
            );
            //todo 整合当前order表与pay_order表
            $code = "<img src='/manage/Pay_modl/enQrcode?url=".$payUrl."'>";
            $msg = ['msg'=>$code,'time'=> getSetting()];
            $this->success( $msg,'', $code);
        }
    }
    public function checkParam()
    {
        $data = [
            'payId' => $this->payId,
            'type'  => $this->type,
            'price' => $this->price,
            'sign'  => $this->sign,
        ];
        $validate = new Validate();
        $validate->rule([
            ['payId','require','请传入商户订单号'],
            ['type','require|in:1,2', '请传入支付方式|支付方式不存在'],
            ['price','require|gt:0', '请传入订单金额|订单金额必须大于0'],
            ['sign','require', '请传入签名'],
        ]);
        if( !$validate->check( $data )) $this->error( $validate->getError() );
    }
    //获取订单信息
    public function getOrder()
    {

        $res = Db::name("pay_order")->where("order_id", input("orderId"))->find();
        if ($res){
            $time = getSetting('close');

            $data = array(
                "payId" => $res['pay_id'],
                "orderId" => $res['order_id'],
                "payType" => $res['type'],
                "price" => $res['price'],
                "reallyPrice" => $res['really_price'],
                "payUrl" => $res['pay_url'],
                "isAuto" => $res['is_auto'],
                "state" => $res['state'],
                "timeOut" => $time,
                "date" => $res['create_date']
            );
            return json($this->getReturn(1, "成功", $data));
        }else{
            return json($this->getReturn(-1, "云端订单编号不存在"));
        }
    }
    //查询订单状态
    public function checkOrder()
    {
        $res = Db::name("pay_order")->where("order_id", input("orderId"))->find();
        if ($res){
            if ($res['state']==0){
                return json($this->getReturn(-1, "订单未支付"));
            }
            if ($res['state']==-1){
                return json($this->getReturn(-1, "订单已过期"));
            }
            $key = getSetting('key');


            $res['price'] = number_format($res['price'],2,".","");
            $res['really_price'] = number_format($res['really_price'],2,".","");


            $p = "payId=".$res['pay_id']."&param=".$res['param']."&type=".$res['type']."&price=".$res['price']."&reallyPrice=".$res['really_price'];

            $sign = $res['pay_id'].$res['param'].$res['type'].$res['price'].$res['really_price'].$key;
            $p = $p . "&sign=".md5($sign);

            $url = $res['return_url'];



            if (strpos($url,"?")===false){
                $url = $url."?".$p;
            }else{
                $url = $url."&".$p;
            }

            return json($this->getReturn(1, "成功", $url));
        }else{
            return json($this->getReturn(-1, "云端订单编号不存在"));
        }

    }
    /**
     * @desc 同步回调接口
     * Created by PhpStorm
     * User: zhaolan
     * Date: 2019/7/2 0002 14:43
     * @return void
     */
    public function returnInfo()
    {
        $key = getSetting('key');
        $payId = $_GET['payId'];//商户订单号
        $param = $_GET['param'];//创建订单的时候传入的参数
        $type = $_GET['type'];//支付方式 ：微信支付为1 支付宝支付为2
        $price = $_GET['price'];//订单金额
        $reallyPrice = $_GET['reallyPrice'];//实际支付金额
        $sign = $_GET['sign'];//校验签名，计算方式 = md5(payId + param + type + price + reallyPrice + 通讯密钥)
        //开始校验签名
        $_sign =  md5($payId . $param . $type . $price . $reallyPrice . $key);
        if ($_sign != $sign) {
            echo "error_sign";//sign校验不通过
            exit();
        }
        //继续业务流程
        echo "商户订单号：".$payId ."<br>自定义参数：". $param ."<br>支付方式：". $type ."<br>订单金额：". $price ."<br>实际支付金额：". $reallyPrice;
    }
    /**
     * @desc 异步回调地址
     * Created by PhpStorm
     * User: zhaolan
     * Date: 2019/7/2 0002 14:47
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     * @return void
     */
    public function notify()
    {
        $key = getSetting('key');
        $payId = $_GET['payId'];//商户订单号
        $param = $_GET['param'];//创建订单的时候传入的参数
        $type = $_GET['type'];//支付方式 ：微信支付为1 支付宝支付为2
        $price = $_GET['price'];//订单金额
        $reallyPrice = $_GET['reallyPrice'];//实际支付金额
        $sign = $_GET['sign'];//校验签名，计算方式 = md5(payId + param + type + price + reallyPrice + 通讯密钥)
        //开始校验签名
        $_sign =  md5($payId . $param . $type . $price . $reallyPrice . $key);
        if ($_sign != $sign) {
            echo "error_sign";//sign校验不通过
            exit();
        }
        Db::name( 'pay_order')->update(['state'=>2],['pay_id'=>$payId]);
        echo "success";
    }

    public function getCurl($url){
        $info = curl_init();
        curl_setopt($info,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($info,CURLOPT_HEADER,0);
        curl_setopt($info,CURLOPT_NOBODY,0);
        curl_setopt($info,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($info,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($info,CURLOPT_SSL_VERIFYHOST,false);
        curl_setopt($info,CURLOPT_URL,$url);
        $output = curl_exec($info);
        curl_close($info);
        return $output;
    }
}