<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/14 0014
 * Time: 13:41
 */

namespace app\business\model;
use think\Model;
use think\Db;
/**
 * 用户模型
 * Class User
 * @package app\manage\model
 */
class Business extends Model
{
    protected $autoWriteTimestamp = true;

    protected function getLastLoginTimeAttr( $value )
    {
        return date( 'Y-m-d H:i:s', $value);
    }
    /**
     * 2019/6/14 0014 13:42
     * @desc 检测用户是否登录
     * @ApiParams
     * @ApiReturnParams
     */
    public function checkLogin()
    {
        if( session('?business') ){
            return true;
        }
        return false;
    }

    /**
     * 2019/6/14 0014 16:00
     * @desc 用户登录信息校验
     * @ApiParams
     * @ApiReturnParams
     * @param array
     * @return array
     */
    public function doLogin( $data )
    {
        $msg = msg();

        if(empty( $data['mobile'])){
            $msg['msg'] = '用户手机号不能为空';
            return $msg;
        }
        $business = $this->where('mobile',$data['mobile'])->find();
        if(!$business){
            $msg['msg'] = '用户不存在！';
            return $msg;
        }
        if($business['check'] == 0){
            $msg['msg'] = '用户信息审核中...请耐心等待！';
            return $msg;
        }
        if($business['check'] == 1){
            $msg['msg'] = '用户信息审核未通过！';
            return $msg;
        }

        if($business['status'] != 1){
            $msg['msg'] = '用户已被禁用，请前往联系客服申请启用！';
            return $msg;
        }

        if(compare_password($business['password'],$data['password'],$business['salt'])){
            $msg['status'] = 1;
            $login = [
                'last_login_time'   => time(),
                'last_login_ip'     => request()->ip()
            ];
            $this->where(['id'=>$business['id']])->update($login);
            session("business",$business);
        } else {
            $msg['msg'] = '密码信息错误';
        }
        return $msg;
    }


    public function changeMoney( $money, $now_money , $id ,$type = 0  )
    {
        $con = [
            'money' => $now_money
        ];
        Db::startTrans();
        try{
            $where = ['id'=> $id];
            //设置商户余额
            Db::name('Business')->where( $where )->update( $con );
            $data = [
                'account'   => $money,
                'bus_id'    => $id,
                'now_account'   => $now_money,
                'info'      => $type,
                'create_time'   => time()
            ];
            //写入商户余额日志
            Db::name( 'AccountLog')->insert( $data );
            return true;
        }catch ( \Exception $e){
            Db::rollback();
            return false;
        };
    }

    /**
     * 2019/7/15 0015 16:30
     * @param $data
     * @return array
     * 注册
     */
    public function register($data){
        $msg = msg();
        $tmp['salt'] = getSalt();
        $tmp['password'] = encode_password($data['password'], $tmp['salt'] );
        $tmp['create_time'] = time();
        $tmp['status'] = 0;
        $tmp['shop_sn'] = Db::name('business')->max("shop_sn")+1;
        $tmp['name'] = '得瑞支付'.$tmp['shop_sn'];
        $tmp['mobile'] = $data['mobile'];
        $res = Db::name('business')->insert($tmp);
        if($res){
            $business = Db::name('business')->where('mobile',$data['mobile'])->find();
            session("business",$business);
            $msg['msg'] = '注册商户成功';
            $msg['status'] = 1;
        }else{
            $msg['msg'] = '注册商户失败';
        }
        return $msg;
    }
}