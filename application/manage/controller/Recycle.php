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
        $id = $this->request->param( 'id/a', 0);
        if( empty( $id ) ){
            $this->error( "参数信息错误！");
        }
        if( $this->request->isPost() && $this->request->isAjax() ){
            $info = $this->model->where( ['id'=>['in',$id]] )->select();
            if( count( $info ) < 1 ){
                $this->error( "数据信息不存在");
            }
            foreach( $info as $val){
                $res = db( $val['table'] )->where( ['id'=>$val['rid']] )->update( ['delete_time'=>0] );
                if( $res ){
                    $this->model->where(['id'=>$val['id']])->delete();
                    operaLog($this->admin_id.'还原信息');
                }
            }
            $this->success( "数据还原成功！");
        }
        $this->error( "请求方式错误！");
    }

    /**
     * 2019/6/6 0006 9:41
     * @desc 真实删除
     * @ApiParams
     * @ApiReturnParams
     */
    public function delete()
    {
        if (request()->isAjax() && request()->isPost()) {
            $id = request()->param('id/a', 0, 'intval');
            if (empty($id)) {
                $this->error('参数错误');
            }
            $where = ['id' =>['in',$id]];
            $info = $this->model->where( ['id'=>['in',$id]] )->select();
            if( count( $info ) < 1 ){
                $this->error( "数据信息不存在");
            }
            foreach( $info as $val){
                $res = db( $val['table'] )->where( ['id'=>$val['rid']] )->delete();
                if( $res ){
                    operaLog($this->admin_id.'删除信息');
                }
            }
            $this->model->where($where)->delete();
            $this->success( "删除成功！");
        }
        $this->error('非法请求类型！');
    }
}