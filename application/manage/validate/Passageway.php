<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/19 0019
 * Time: 11:13
 */

namespace app\manage\validate;


use think\Validate;

class Passageway extends Validate
{
 protected  $rule = [
     ['name', 'require', '通道名称不能为空'],
     ['pay_type', 'require|unique:passageway', '通道编码不能为空|通道编码已存在'],
     ['rate', 'require|lt:1', '费率不能为空|费率最大值不能超过1'],
 ];
}