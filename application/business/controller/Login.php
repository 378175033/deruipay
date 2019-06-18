<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/14 0014
 * Time: 13:17
 */

namespace app\business\controller;
use think\Controller;
use think\captcha\Captcha;

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
        if( model( 'Business')->checkLogin() ){
            $this->error("您已经登录了！", '/business.html#/Index/welcome' );
        }
        if( $this->request->isAjax() && $this->request->isPost() ){
            $data = $this->request->param();
            //验证数据信息
            $validate = validate('Login');
            if( !$validate->check( $data ) ){
                $this->error( $validate->getError() );
            }
            $res = model( 'Business')->doLogin( $data );
            if( empty( $res['status'] ) ){
                $this->error( $res['msg'] );
            }
            //写入登录日志
            model( "LoginLog")->addLog();
            $this->success( "登录成功！", '/business.html#/Index/welcome');
        }
        return $this->fetch('/login');
    }

    /**
     * 2019/6/14 0014 16:37
     * @desc
     * @ApiParams
     * @ApiReturnParams
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
     * @param $id int
     * @return bool
     */
    public function check_verify( $code, $id = ""){
        $captcha = new Captcha();
        return $captcha->check($code, $id);
    }

    public function logout()
    {
        session( 'business', null);
        $this->redirect( url( 'Login/index') );
    }
}