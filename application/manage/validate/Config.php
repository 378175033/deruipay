<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/7/15 0015
 * Time: 10:53
 */

namespace app\manage\validate;


use think\Validate;

class Config extends Validate
{
    protected $rule = [
        ['vname','require|alphaDash|unique:config','字段英文名称必需|字段只能是字母或数字_的组合|字段英文名称不能重复']
    ];
}