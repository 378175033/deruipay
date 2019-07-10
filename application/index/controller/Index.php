<?php
namespace app\index\controller;

use think\Controller;
use think\Validate;

class Index extends Controller
{
    public function index()
    {
        return $this->fetch();
    }

    public function check()
    {
        if( $this->request->isAjax() && $this->request->isPost() )
        {
            $flag = request()->post( "flag",0,'intval');
            switch ( $flag ){
                case 1:
                    $rule = [
                        ['price','require|float','金额不能为空|金额错误'],
                        ['type','require','请选择支付类型']
                    ];
                    $url = url('index/pay');
                    break;
                default:
                    $rule = [];
                    $url = '';
                    $this->error( "请求参数错误！");
            }
            $validate = new Validate();
            $validate->rule( $rule );
            $data = request()->param();
            if( !$validate->check( $data ) ){
                $this->error( $validate->getError() );
            }
            $this->success( "成功！", $url);
        }
        $this->error( "请求方式错误！");
    }

}
