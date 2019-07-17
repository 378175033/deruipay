<?php
namespace app\index\controller;

use app\business\model\Business;
use app\business\model\LoginLog;
use app\index\model\Suggest;
use think\Controller;
use think\Loader;
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

    public function intrduce()
    {
        return $this->fetch();
    }

    public function api()
    {
        return $this->fetch();
    }

    public function download()
    {
        return $this->fetch();
    }

    public function contact()
    {
        return $this->fetch();
    }

    public function suggest()
    {
        return $this->fetch();
    }

    public function information()
    {
        return $this->fetch();
    }

    public function agreement()
    {
        return $this->fetch();
    }

    public function suggest_form()
    {
        $request = $this->request->param();
        $validate = Loader::validate('Suggest');
        $result = $validate->check($request);
        if(true !== $result){
            $this->error($validate->getError());
        }
        $suggest = new Suggest($request);
        $suggest->allowField(true)->save();
        $this->success('提交成功！');
    }

    public function register(){
        $request = $this->request;
        $Business = new Business();
        if($request->isAjax() && $request->isPost()){

            $this->verify($request->param());

            $business = $Business->where('mobile',$request->param('mobile'))->find();

            if($business){
                $this->error('抱歉，该手机已注册！');
            }

            $buss = $Business->register($request->param());
            if(empty( $buss['status'])){
                $this->error( $buss['msg']);
            }
            $this->success($buss['msg'],'');

        }
    }

    public function retrieve(){
        $request = $this->request;
        $Business = new Business();
        if($request->isAjax() && $request->isPost()){

            $this->verify($request->param());

            $buss = $Business->retrievePwd($request->param());

            if(empty( $buss['status'])){
                $this->error( $buss['msg']);
            }
            $this->success($buss['msg']);
        }
    }


    public function verify($data){
        $rule = [
            'mobile|手机号'=> 'require',
            'password|密码' => 'require',
            'code|验证码' => 'require|integer',
        ];

        $validate = new Validate($rule);
        if(!$validate->check($data)){
            $this->error($validate->getError());
        }

        if( !preg_match( '/^1[3456789]\d{9}$/', $data['mobile'] ) ) $this->error( "手机号格式错误！");
        //校验手机验证码
//        if( !checkSms($data['code']) ){
//            $this->error( "验证码错误！");
//        }
    }
}
