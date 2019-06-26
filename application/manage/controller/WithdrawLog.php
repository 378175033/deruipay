<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/19 0019
 * Time: 15:47
 */

namespace app\manage\controller;


use app\common\controller\Manage;

class WithdrawLog extends Manage
{
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        $this->table = 'withdraw_log';
        $this->join = [
            ['business b','b.id = a.bus_id','left']
        ];
        $this->field = "a.*,b.name";
        $this->model = model($this->table);
    }

}