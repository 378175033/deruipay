<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/7/19 0019
 * Time: 11:34
 */

namespace app\common\model;


use app\common\controller\Curl;
use app\index\model\Business;
use app\index\model\Certificate;
use think\Exception;
use think\Model;

class Notify extends Model
{
    /**
     * 2019/7/19 0019 11:39
     * @param $order
     * @param $business_id
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 回调第三方推送
     */
    public function verify($order,$business_id){

        $Business = new Business();
        $business = $Business->find($business_id);

        if(!$business){
            $this->error('商户不存在');
        }
        $time = time();

        $sign = getSign($business['api_key'],$business['api_secret'],$business['shop_sn'],$time,$order['batch']);

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
            'order_sn'=>$order['batch'],
        ];
        try{
            $curl = $Curl->post($business['notify_url'],$tmp);
            if($curl){
                return '回调请求成功';
            }else{
                $this->error('回调请求失败');
            }
        }catch (Exception $e){
            $this->error($e->getMessage());
        }
    }
}