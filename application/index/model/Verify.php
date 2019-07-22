<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/7/19 0019
 * Time: 15:29
 */

namespace app\index\model;


use app\common\controller\Curl;
use app\index\controller\Key;
use think\Exception;
use think\Log;
use think\Model;
use think\Validate;

class Verify extends Model
{
    /**
     * 2019/7/19 0019 15:41
     * @param string $content
     * @param $business_id
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function verifyParam($datas)
    {
        $enData = $datas['enData'];
        $data = $this->certificateDecrypt($enData);//证书验证

        $white = $this->whitelist($data['business_id']);//白名单验证

        if(!$white['status']){
           return $white;
        }
        $data = $this->verifySign($data,$datas['order_sn']);//签名验证

        return $data;

    }
    /**
     * 2019/7/19 0019 15:39
     * @param $business_id
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 验证白名单
     */
    public function whitelist($business_id){
        $Business = new Business();

        if(!isset($_SERVER['HTTP_REFERER'])){
            return msg("No refer found");
        }
        $host = gethostbyname($_SERVER['HTTP_REFERER']);//ip

        $business = $Business->find($business_id);

        $allowIp = explode("\n",$business['allow_ip']);

        if(!in_array($host,$allowIp)){
            return msg("不好意思，商户不在白名单内，请联系管理员！");
        }else{
            return msg('请求成功',1);
        }
    }
    /**
     * 2019/7/19 0019 15:36
     * @param $content
     * @return bool|string
     * 证书验证
     */
    public function certificateDecrypt($content){

        //证书验证
//        $Key = new Key();
//
//        $content = $Key->decrypt($content);

        $Certificate = new Certificate();
        $content = $Certificate->authcode($content,'D');


        if(!$content){
            return msg("证书验证错误");
        }
        $res = [];
        $data = explode('&',$content);
        foreach($data as $value){
            $arr = explode('=', $value);
            $res[$arr[0]] = $arr[1];
        }

        return $res;

    }


    /**
     * 2019/7/18 0018 13:05
     * @param $request
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 签名验证
     */
    public function verifySign($param,$ordr_sn){


        $rule = [
            'sign|签名' => 'require',
            'timestamp|时间' => 'require',
        ];

        $validate = new Validate($rule);
        $result = $validate->check($param);
        if (!$result) {
            return msg($validate->getError());
        }
        $Business = new Business();
        $business = $Business->where('shop_sn',$param['business_id'])->find();

        $key = $business['api_key'];

        if(!$business){
            return msg("查无商户");
        }

        $sign = getSign($key,$business['api_secret'],$param['business_id'],$param['timestamp'],$ordr_sn);

        if($param['sign'] != $sign){
            return msg("签名错误");
        }
        $param['status'] = 1;
        return $param;
    }


    /**
     * 2019/7/19 0019 11:39
     * @param $order
     * @param $business_id
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 回调第三方推送
     */
    public function verifyNotify($order,$business_id){
        Log::info('测试回调请求');
        $Business = new Business();
        $business = $Business->find($business_id);

        if(!$business){

            return msg('商户不存在');
        }
        $time = time();

        $sign = getSign($business['api_key'],$business['api_secret'],$business['shop_sn'],$time,$order['order_sn']);
        Log::info('签名：'.$sign);
        $Curl = new Curl();
        $data = [
            'sign'=>$sign,
            'timestamp'=>$time,
            'business_id'=>$business['shop_sn'],
        ];
        ksort($data);

        $content = http_build_query($data);

        $Certificate = new Certificate();

        $content = $Certificate->authcode($content,'E',$business['shop_sn']);
        $tmp =[
            'enData'=>$content,
            'order_sn'=>$order['order_sn'],
        ];
        Log::info($tmp);
        //try{
            $curl = $Curl->post($business['notify_url'],$tmp);
            if($curl){
                return msg('回调请求成功',1);
            }else{
                return msg('回调请求失败');
            }
//        }catch (Exception $e){
//            return msg($e->getMessage());
//        }
    }
}