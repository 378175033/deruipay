<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/7/1 0001
 * Time: 15:34
 */

namespace app\index\controller;


use think\Controller;
use think\Log;
use think\Request;

class Notify extends Controller
{

    /**
     * 2019/6/28 0028 10:31
     * @param Request $request
     * 支付宝回调
     */
    public function aliPayNotify()
    {
        Log::info($this->request->post());
        //为post为登录
        $api = new \app\manage\controller\Api();
        return $api->succNotifyServer();
    }

}