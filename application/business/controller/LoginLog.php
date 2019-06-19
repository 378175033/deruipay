<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/6 0006
 * Time: 13:11
 */
namespace app\business\Controller;
use app\common\controller\Business;

class LoginLog extends Business
{
    public function _initialize()
    {
        parent::_initialize();
        $this->table = 'LoginLog';
        $this->model = model($this->table);
        $this->where = [
            'type'      => 1,
            'user_id'   => session( 'business')['id']
        ];
    }
}