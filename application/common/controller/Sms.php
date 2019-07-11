<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/7/11 0011
 * Time: 14:30
 */

namespace app\common\controller;

use think\Controller;

class Sms extends Controller
{

    /**
     * @desc 获取短信验证码
     * Created by PhpStorm
     * User: zhaolan
     * Date: 2019/7/11 0011 15:18
     * @throws \think\Exception
     * @return void
     */
    public function code()
    {
        if( $this->request->isAjax() ){
            $mobile = $this->request->param('mobile', 0);
            $content = $this->request->param('text','');
            $use = $this->request->param('use', 'business');
            if( empty( $mobile )) $this->error( "手机号不得为空!");
            if( !preg_match( '/^1[3456789]\d{9}$/', $mobile ) ) $this->error( "手机号格式错误！");
            //查看是否存在手机号码
            $res = db( $use )->where( 'mobile', $mobile)->count();
            if( !$res ){
                $this->error("该手机号未注册！");
            }
            $res = model( "Sms")->sendCode( $mobile, $content);
            if( $res['stat'] == 100 ){
                $this->success( $res['message'] );
            }
            $this->error( $res['message'] );
        }
        $this->error("请求方式错误1!");
    }

}