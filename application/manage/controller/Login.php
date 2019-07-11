<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/14 0014
 * Time: 13:17
 */

namespace app\manage\controller;
use think\Controller;
use think\captcha\Captcha;
use think\Image;

/**
 * Class Login
 * @desc 后台用户登录类
 * @package app\manage\controller
 */
class Login extends Controller
{
    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 2019/6/14 0014 15:06
     * @desc登录页面
     * @ApiParams
     * @ApiReturnParams
     */
    public  function index()
    {
        if( model( 'User')->checkLogin() ){
            $this->error("您已经登录了！", '/manage.html#/Index/welcome' );
        }
        if( $this->request->isAjax() && $this->request->isPost() ){
            $data = $this->request->param();
            //验证数据信息
            $validate = validate('Login');
            if( !$validate->check( $data ) ){
                $this->error( $validate->getError() );
            }
            //校验手机验证码
            if( !checkSms( $data['code'] ) ){
                $this->error( "验证码错误！");
            }
            $res = model( 'User')->doLogin( $data );
            if( empty( $res['status'] ) ){
                $this->error( $res['msg'] );
            }
            session('smsCode', null);
            //写入登录日志
            model( "LoginLog")->addLog();
            $this->success( "登录成功！", '/manage.html#/Index/welcome');
        }
        return $this->fetch('/login');
    }

    /**
     * 2019/6/14 0014 16:37
     * @desc
     * @ApiParams
     * @ApiReturnParams
     * @param $id string
     * @return resource
     */
    public function entry( $id = "")
    {
        $config =    [
            // 验证码字体大小
            'fontSize'    =>    17,
            // 验证码位数
            'length'      =>    4,
            // 关闭验证码杂点
            'useNoise'    =>    false,
            'imageW'    => 140
        ];
        $captcha = new Captcha($config);
        return $captcha->entry( $id );
    }

    /**
     * 2019/6/14 0014 16:35
     * @desc 校验验证码
     * @ApiParams
     * @ApiReturnParams
     * @param $code string
     * @param $id
     * @return bool
     */
    public function check_verify( $code, $id = ""){
        $captcha = new Captcha();
        return $captcha->check($code, $id);
    }

    /**
     * 2019/6/19 0019 14:32
     * @desc 退出登录
     * @ApiParams
     * @ApiReturnParams
     */
    public function logout()
    {
        session( 'userInfo', null);
        $this->redirect( url( 'Login/index') );
    }
}