<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/18 0018
 * Time: 15:10
 */

namespace app\manage\controller;

use app\common\controller\Manage;

/**
 * @desc 后端首页
 * Class Index
 * @package app\manage\controller
 */
class Order extends Manage
{

    public function _initialize()
    {
        parent::_initialize();
        $this->table = "order";
        $this->model = model($this->table);
    }

    public function index()
    {
        $data1 = ["1","2","3"];
        $data2 = ["4","5","6"];
        $all = 0;
        foreach($data1 as &$x){
            $all += $x;
        }
        echo $x;
#注意这里$x是$data1最后一个元素的引用
        $all2 = 0;
        foreach($data2 as $x=>$y){
            $all2 += ($y+$data1[$x]); //原意是计算:1+4 + 2+5 + 3+6 的总和,结果应该是21
        }
        echo $all2;
//        if (request()->isAjax() && request()->isPost()) {
//            ;
//            return request()->post();
//        }
//        return $this->fetch();
    }

    public function pl()
    {
        $data[[
            'name'  =>  'thinkphp',
            'email' =>  'thinkphp@qq.com'
        ]];
        foreach ($data as &$v) {

        }
        $this->model->saveAll($data);
    }
}