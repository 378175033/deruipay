<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/17 0017
 * Time: 10:21
 */

namespace app\manage\validate;
use think\Validate;

class User extends Validate
{
    protected $rule = [
//        ['username','require|unique:user',"用户名/登录名不能为空|用户名已存在"],
        ['mobile','require|unique:user',"手机号不能为空|手机号已存在"],
    ];
}