<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/14 0014
 * Time: 13:19
 */

namespace app\business\controller;
use app\common\controller\Business;

/**
 * @desc 后端首页
 * Class Index
 * @package app\manage\controller
 */
class Index extends Business
{
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
    }


    public function index()
    {
        return $this->fetch();
    }

    public function welcome()
    {
        return 123;
    }
}