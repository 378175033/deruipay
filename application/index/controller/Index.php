<?php
namespace app\index\controller;

use app\business\controller\Login;
use app\business\model\Business;
use app\business\model\LoginLog;
use app\common\model\Sms;
use think\Controller;
use think\Validate;

class Index extends Controller
{
    public function index()
    {
        return $this->fetch();
    }
    /**
     * 2019/7/11 0011 13:15
     * 商户端登录
     */
    public function login(){
        $request = $this->request;
        /**
         * 先判断商户登录过没有
         */
        $Business = new Business();
        if( $Business->checkLogin() ){
            $this->success("您已经登录了！", '/business.html#/index/welcome' );
        }
        if($request->isAjax() && $request->isPost() ){
            $rule = [
                'password|密码' => 'require',
                'code|验证码' => 'require|integer',
            ];

            $validate = new Validate($rule);
            if(!$validate->check($request->param())){
                $this->error($validate->getError());
            }
            //校验手机验证码
            if( !checkSms( $request->param('code') ) ){
                $this->error( "验证码错误！");
            }

            $business = $Business->doLogin($request->param());
            if(empty( $business['status'])){
                $this->error( $business['msg']);
            }
            //写入登录日志
            $LoginLog = new LoginLog();
            session( "smsCode", null);
            $LoginLog->addLog();
            $this->success( "登录成功！", '/business.html#/index/welcome');
        }
    }

    /**
     * Created by PhpStorm
     * User: Administrator
     * Date: 2019/6/27 0027
     * Time: 18:04
     * @desc
     * void
     */
    public function logout()
    {
        session( 'business', null);
        $this->redirect( url( 'index/index') );
    }
}
