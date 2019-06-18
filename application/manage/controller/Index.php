<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/14 0014
 * Time: 13:19
 */

namespace app\manage\controller;
use app\common\controller\Manage;

/**
 * @desc 后端首页
 * Class Index
 * @package app\manage\controller
 */
class Index extends Manage
{
    public function index()
    {
        $this->assign( 'menu', model( 'menu')->treeMenu() );
        return $this->fetch();
    }

    public function welcome()
    {
        echo 123;
    }
}