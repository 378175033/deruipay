<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/14 0014
 * Time: 15:33
 */

namespace app\manage\validate;
use think\Validate;

class Login extends Validate
{
    protected $rule = [
        ['code','require', '验证码不能为空！'],
        ['username','require', '登录用户名不能为空！'],
        ['password','require', '登录密码不能为空！'],
    ];
}