<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/7/10 0010
 * Time: 15:21
 */

namespace app\manage\model;


use think\Model;

class Suggest extends Model
{
    protected $autoWriteTimestamp = true;
    protected $insert = ['status' => 0];
}