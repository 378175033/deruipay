<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/18 0018
 * Time: 15:06
 */

namespace app\manage\validate;


use think\Validate;

class Business extends Validate
{
    protected $rule = [
        ['login_name','require|alphaDash|unique:business',"用户名/登录名不能为空|用户名/登录名只能为字母与数字_-|用户名/登录名已存在"],
        ['mobile','unique:business',"手机号码已存在"],
    ];
}