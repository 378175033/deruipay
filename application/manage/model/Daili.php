<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/24 0024
 * Time: 14:11
 */

namespace app\manage\model;


use think\Model;

class Daili extends Model
{

    protected $autoWriteTimestamp = true;
    protected function getCheckTimeAttr( $value )
    {
        return date("Y-m-d H:i:s", $value);
    }
}