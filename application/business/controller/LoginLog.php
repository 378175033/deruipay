<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/6 0006
 * Time: 13:11
 */
namespace app\manage\Controller;
use app\common\controller\Manage;

class LoginLog extends Manage
{
    public function _initialize()
    {
        parent::_initialize();
        $this->table = 'LoginLog';
        $this->model = model($this->table);
    }
}