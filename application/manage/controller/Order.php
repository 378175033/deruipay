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
        if ($this->request->isPost() && $this->request->isAjax()) {
            $page = $this->request->param('page', 1, 'intval');
            $per = $this->request->param('limit', 10, 'intval');
            $this->order = $this->request->param('order', $this->order);
            $where = [
                'delete_time' => 0,
            ];
            $param = $this->request->param();
            foreach ($param as $key => $val) {
                $ps = substr($key, 0, 2);
                $vl = substr($key, 2);
                switch ($ps) {
                    case 'l-':
                        if (!empty($val)) $where[$vl] = ['like', '%' . $val . '%'];
                        break;
                    case 'e-':
                        if (!empty($val)) $where[$vl] = $val;
                        break;
                    case 'i-':
                        if (!empty($val)) $where[$vl] = ['in', $val];
                        break;
                    default:
                        break;
                }
            }
            $defaulttime = $this->request->param('defaulttime');
            if (empty($defaulttime)) {
                $stime = $this->request->param('stime', 0);
                $ltime = $this->request->param('ltime', 0);
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
            } else {
                $this->model->whereTime('create_time', $defaulttime);
            }
            $business_name = $this->request->param('business_name');
            if (!empty($business_name)) {
                $business = model('business')->where(['name' => ['like', '%' . $business_name . '%']])->field("id")->select();
                if (!empty($business)) {
                    $where['business_id'] = ['in', array_column($business, 'id')];
                } else {
                    $this->error('商户名称错误');
                }
            }
            $business_id = $this->request->param('business_id');
            !empty($business_id) && $where['business_id'] = $business_id;
            $passageway_id = $this->request->param('passageway_id');
            if (!empty($passageway_id)) {
                $passageway = model('user_passageway')->where(['passageway_id' => $passageway_id])->field("business_id")->select();
                if (!empty($passageway)) {
                    $where['business_id'] = ['in', array_column($passageway, 'business_id')];
                } else {
                    $this->error('通道错误');
                }
            }
            $page = $page - 1;
            $list = $this->model
                ->field($this->field)
                ->where($where)
                ->limit($page * $per, $per)
                ->order($this->order)
                ->select();
            $sql = $this->model->getLastSql();
            $this->model->where($where);
            !empty($defaulttime) && $this->model->whereTime('create_time', $defaulttime);
            !empty($business_name) && $where['business_id'] = ['in', array_column($business, 'id')];
            $count = $this->model->count();
            $data = [
                'list' => $list,
                'count' => $count,
                'sql' => $sql
            ];
            $this->success('获取成功！', '', $data);
        }
        $passageway_list = controller('PayModl')->get4select();
        $this->assign('passageway_list', $passageway_list);
        return $this->fetch();
    }

    public function pl()
    {
        for ($i = 0; $i < 10000; $i++) {
            $data[] = [
                'business_id' => mt_rand(1, 10),
                'batch' => md5(mt_rand()),
                'user_passageway_id' => mt_rand(1, 10),
                'pay_info' => "{" . mt_rand() . "}",
                'pay_from' => mt_rand(0, 1),
                'bank_id' => mt_rand(0, 100),
                'amount' => (float)(mt_rand(0, 9999999) . '.' . mt_rand(0, 99)),
                'commission' => mt_rand(0, 99) / 100,
                'service_charges' => mt_rand(0, 10),
                'create_time' => mt_rand(1526699151, 1560913551),
                'status' => mt_rand(0, 5),
                'back_status' => mt_rand(0, 2),
                'back_info' => mt_rand(),
            ];
        }
        $this->model->saveAll($data);
    }
}
