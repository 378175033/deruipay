<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/18 0018
 * Time: 14:08
 */

namespace app\manage\controller;


use app\common\controller\Manage;

class Withdraw extends Manage
{
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        $this->table = "Withdraw";
        $this->model = model($this->table);
    }

    /**
     *  提款审核
     * @return mixed
     * @throws \think\Exception
     */
    public function check_status()
    {
        $id = $this->request->param('id', 0, 'intval');
        if (empty($id)) {
            $this->error('参数错误');
        }
        $where = [
            'id' => $id,
            'delete_time' => 0
        ];
        $field = 'id,status,check_desc,check_time,money';
        $data = $this->model->where($where)->field($field)->find();
        if (!$data) {
            $this->error('该申请不存在');
        }
        if (request()->post()) {
            $post_data = $this->request->param('');
            if (!isset($post_data['status'])) {
                $this->error('请选择状态');
            }
            $post_data['check_time'] = time();
            $result = $this->model->allowField(true)->save($post_data, ['id' => $id]);
            if ($result) {
                \app\manage\model\Withdraw::addWithdrawLog($post_data);
                $this->success("设置成功！");
            }
            $this->error("请稍后再试！");
        }
        $data['check_time'] = date('Y-m-d H:i:s', $data['check_time']);
        $this->assign('data', $data);
        return $this->fetch();
    }

    /**
     *  提款软删除
     */
    public function remove()
    {
        if (request()->isPost()) {
            $id = $this->request->param('id', 0, 'intval');
            if (!$id) {
                $this->error('参数错误');
            }
            $res = $this->model->where('id', $id)->find();
            if (!$res) {
                $this->error('商户不存在');
            }
            $res->delete_time = time();
            $result = $res->save();
            if (!$result) {
                $this->error('移除失败');
            }
            $this->success('移除成功');
        }
    }

}