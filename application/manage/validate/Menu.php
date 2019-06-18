<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/17 0017
 * Time: 13:16
 */

namespace app\manage\validate;
use think\Validate;

class Menu extends Validate
{
    protected $rule = [
        ['name', 'require', '菜单名称不能为空'],
        ['controller','require|alphaDash','控制器名称不能为空|控制器名称只能为字母与数字'],
        ['action','require|alphaDash','方法名称不能为空|方法名称只能为字母与数字']
    ];
}