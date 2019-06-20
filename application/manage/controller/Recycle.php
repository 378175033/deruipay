<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/5 0005
 * Time: 17:54
 */
namespace app\manage\Controller;
use app\common\controller\Manage;

class Recycle extends Manage
{
    public function _initialize()
    {
        parent::_initialize();
        $this->table = 'Recycle';
        $this->model = model( $this->table );
    }

    public function  reduction()
    {
        $id = $this->request->param( 'id', 0, 'intval');
        if( empty( $id ) ){
            $this->error( "参数信息错误！");
        }
        if( $this->request->isPost() && $this->request->isAjax() ){
            $info = $this->model->where( ['id'=>$id] )->find();
            if( $info ){
                $res = db( $info['table'] )->where( ['id'=>$info['rid']] )->update( ['delete_time'=>0] );
                if( $res ){
                    $this->model->where(['id'=>$id])->delete();
                    operaLog($this->admin_id.'还原信息');
                    $this->success( "还原成功！");
                }
                $this->error( "还原信息不存在！");
            }
            $this->error( "数据信息不存在！");
        }
        $this->error( "请求方式错误！");
    }
}