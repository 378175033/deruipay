<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/19 0019
 * Time: 16:40
 */

namespace app\manage\validate;
use think\Validate;

class Notice extends Validate
{
    protected $rule = [
        ['name',"require","公告标题不能为空"]
    ];
}