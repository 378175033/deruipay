<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/7/16 0016
 * Time: 15:06
 */
namespace app\index\validate;

use think\Validate;

class Suggest extends Validate
{
    protected $rule = [
        'name|姓名'  => 'require|max:25',
        'phone|手机号码' => 'require|integer',
        'email|邮箱' => 'require|email',
        "desc|描述"  => 'require|min:10',
    ];

}