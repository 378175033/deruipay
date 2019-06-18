<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/14 0014
 * Time: 15:33
 */

namespace app\business\validate;
use think\Validate;

class Login extends Validate
{
    protected $rule = [
        ['captcha','require|captcha', '验证码不能为空！|验证码错误！'],
        ['username','require|token', '登录用户名不能为空！|token验证失败'],
        ['password','require', '登录密码不能为空！'],
    ];
}