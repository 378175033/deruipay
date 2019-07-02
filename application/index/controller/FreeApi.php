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

class FreeApi extends Controller
{
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
        $res2 = Db::name("setting")->where("vkey","key")->find();
        $key = $res2['vvalue'];
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
        $this->closeEndOrder();
        $res2 = Db::name("setting")->where("vkey","key")->find();
        $key = $res2['vvalue'];
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
            Db::name("tmp_price")
                ->where("oid",$res['order_id'])
                ->delete();
            Db::name("pay_order")->where("id",$res['id'])->update(array("state"=>1,"pay_date"=>time(),"close_date"=>time()));

            $url = $res['notify_url'];

            $res2 = Db::name("setting")->where("vkey","key")->find();
            $key = $res2['vvalue'];

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
        $res = Db::name("setting")->where("vkey","lastheart")->find();
        $lastheart = $res['vvalue'];
        if ((time()-$lastheart)>60){
            Db::name("setting")->where("vkey","jkstate")->update(array("vvalue"=>0));
        }
        $time = Db::name("setting")->where("vkey", "close")->find();

        $closeTime = time()-60*$time['vvalue'];
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
}