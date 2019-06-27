<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/19 0019
 * Time: 15:23
 */

namespace app\business\controller;
use app\common\controller\Business;

class User extends Business
{
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        $this->table = "business";
        $this->model = model( "business");
    }

    /**
     * 2019/6/19 0019 15:24
     * @desc 商户基本信息
     * @ApiParams
     * @ApiReturnParams
     */
    public function profile()
    {
        return $this->fetch();
    }

    /**
     * 2019/6/26 0026 15:01
     * @desc获取通道列表
     * @ApiParams
     * @ApiReturnParams
     */
    public function passageway()
    {
        if($this->request->isPost() && $this->request->isAjax()){

            $page = $this->request->param('page', 1, 'intval');
            $per = $this->request->param('limit', 10, 'intval');
            $this->order = $this->request->param('order', $this->order);
            $where = [
                'a.status'    => 1,
                'a.delete_time'   => 0
            ];
            $ns = "(select * from ".config("database.prefix")."user_passageway where business_id =".$this->user['id'].")";
            $list = db('passageway')
                ->alias('a')
                ->field('a.id,a.name,a.pay_type,a.rate,b.rate uRate,b.cost,b.status,b.id uid,b.business_id')
                ->join([
                    [ $ns.' b','b.passageway_id = a.id','left'],
                ])
                ->where( $where )
                ->limit( ($page-1)*$per, $per)
                ->order( 'a.id asc' )
                ->select();
            $sql = $this->model->getLastSql();
            $count = db('passageway')
                ->alias('a')
                ->where($where)
                ->count();
            $data = [
                'list' => $list,
                'count' => $count,
                'sql'   => $sql
            ];
            $this->success('获取成功！', '', $data);
        }
        return $this->fetch();
    }

    /**
     * 2019/6/27 0027 9:27
     * @desc收款管理账号
     * @ApiParams
     * @ApiReturnParams
     */
    public function account()
    {
        $id = $this->user['id'];
        if( $this->request->isAjax() && $this->request->isPost() )
        {
            $param = $this->request->param();
            $res = model('account')->allowField( true )->isUpdate( true )->save( $param, ['business_id'=>$id] );
            if( $res ){
                $this->success( "配置成功！");
            }
            $this->error( "系统繁忙！请稍后再试！");
        }
        $where = [
            'business_id'   => $id
        ];
        $data = model('account')->where( $where )->find();
        if( !$data ){
            $data = $where;
            model('account')->allowField( true )->isUpdate( false )->save( $data );
            $data['id'] = model('account')->id;
        }
        $this->assign( 'data', $data);
        return $this->fetch();
    }

    /**
     * 2019/6/27 0027 13:10
     * @desc 商户余额日志
     * @ApiParams
     * @ApiReturnParams
     * @throws \think\Exception
     */
    public function accountLog()
    {
        if ($this->request->isPost() && $this->request->isAjax()) {
            $page = $this->request->param('page', 1, 'intval');
            $per = $this->request->param('limit', 10, 'intval');
            $this->order = $this->request->param('order', $this->order);
            $stime = $this->request->param('stime', 0);
            $ltime = $this->request->param('ltime', 0);
            $where = [
                'bus_id'    => $this->user['id']
            ];
            if (empty($stime) && !empty($ltime)) {
                $ltime = strtotime($ltime);
                $where['create_time'] = ['<=', $ltime];
            }
            if (!empty($stime) && empty($ltime)) {
                $stime = strtotime($stime);
                $where['create_time'] = ['>', $stime];
            }
            if (!empty($stime) && !empty($ltime)) {
                $ltime = strtotime($ltime);
                $stime = strtotime($stime);
                $where['create_time'] = ['between', [$stime, $ltime]];
            }

            $page = $page - 1;
            $list = model('accountLog')
                ->field( "*")
                ->where($where)
                ->limit($page * $per, $per)
                ->order($this->order)
                ->select();
            $sql = model('accountLog')->getLastSql();
            $count = model('accountLog')->where($where)->count();
            $data = [
                'list' => $list,
                'count' => $count,
                'sql'   => $sql
            ];
            $this->success('获取成功！', '', $data);
        }
        return $this->fetch();
    }

    /**
     * withdraw
     * 2019/6/27 0027 18:02
     * @desc 提现列表
     * mixed
     */
    public function withdraw()
    {
        $id = $this->user['id'];
        if( $this->request->isAjax() && $this->request->isPost() )
        {
            $param = $this->request->param();
            $param['bus_id'] = $id;
            $res = model('withdraw')->doInsert( $param );
            if( empty( $res['status'] ) ){
                $this->error( $res['msg'] );
            }
            if( !empty( $res['status'] ) ){
                $this->success( "申请成功！请耐心等待审核");
            }
            $this->error( "系统繁忙！请稍后再试！");
        }

        return $this->fetch();
    }
}