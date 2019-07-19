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

        $sign = getSign($business['api_key'],$time,$business['api_secret']);

        $Curl = new Curl();

        $data = [
            'key'=>$business['api_key'],
            'timestamp'=>$time,
            'sign'=>$sign,
            'business_id'=>$business['shop_sn'],
            'order'=>$order,
        ];
        try{
            $curl = $Curl->post($business['notify_url'],$data);
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