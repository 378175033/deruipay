<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/18 0018
 * Time: 14:09
 */

namespace app\manage\model;


use think\Model;

class Log extends Model
{
    public static function addLog($opera = '')
    {
        if(empty($opera)){
            $opera = request()->controller().'/'.request()->action();
        }
        $userInfo = session('userInfo')->getData();
        $data = [
            'username'          =>   $userInfo[ 'username' ],
            'nickname'          =>   $userInfo[ 'nickname' ],
            'device'            =>   clientOS(),
            'ip'                =>   GetIpLookup(),
            'create_time'       =>   time(),
            'opera_type'        =>   $opera
        ];
        self::create($data);
    }
}