<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/18 0018
 * Time: 14:49
 */

namespace app\manage\model;
use think\Model;

class Business extends Model
{
    protected $autoWriteTimestamp = true;
    protected function getCheckTimeAttr( $value )
    {
        return date("Y-m-d H:i:s", $value);
    }
    protected function getLastLoginTimeAttr( $value )
    {
        return date( 'Y-m-d H:i:s', $value);
    }
}