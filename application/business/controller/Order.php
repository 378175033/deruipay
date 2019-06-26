<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/24 0024
 * Time: 17:46
 */

namespace app\business\controller;
use app\common\controller\Business;

class Order extends Business
{
    private $pre = "ss";
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        $this->pre = config("database.prefix");
        $this->table = "Order";
        $this->model = model( "Order");
    }

    public function index()
    {
        if( $this->request->isPost() && $this->request->isAjax() )
        {
            $page = $this->request->param('page', 1, 'intval');
            $per = $this->request->param('limit', 10, 'intval');
            $where = [
                'a.business_id'   => $this->user['id'],
                'a.delete_time'   => 0
            ];
            $join = [
                [$this->pre."passageway p",'p.id = a.user_passageway_id', 'left'],
            ];
            $field = "a.id,p.name,a.create_time";
            $list = $this->model
                ->alias( "a")
                ->field( $field )
                ->join( $join )
                ->where( $where )
                ->limit( $page*$per, $per)
                ->select();
            $sql = $this->model->getLastSql();
            $count = $this->model
                ->where( $where )
                ->count();
            $this->success( "数据加载成功！",'',['list'=>$list,'count'=>$count,'sql'=>$sql]);
        }
        return $this->fetch();
    }
}