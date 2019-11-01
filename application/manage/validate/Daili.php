<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/18 0018
 * Time: 15:06
 */

namespace app\manage\validate;


use think\Validate;

class Daili extends Validate
{
    protected $rule = [
        ['name','require|unique:daili',"用户名不能为空|用户名已存在"],
        ['phone','require|unique:daili',"手机号码不能为空|手机号码已存在"],
    ];
}