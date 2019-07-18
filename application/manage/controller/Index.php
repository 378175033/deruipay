<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/14 0014
 * Time: 13:19
 */

namespace app\manage\controller;

use app\common\controller\Manage;
use app\index\model\Suggest;
use think\Db;

/**
 * @desc 后端首页
 * Class Index
 * @package app\manage\controller
 */
class Index extends Manage
{
    public function index()
    {
        $this->assign('menu', model('menu')->treeMenu());
        return $this->fetch();
    }

    /**
     * @desc 后台首页
     * Created by PhpStorm
     * User: zhaolan
     * Date: 2019/7/9 0009 11:04
     * @return mixed
     */
    public function welcome()
    {
        //获取订单总共的收入
        $model = model("order");
        $where = ['status' => 1];
        //总入金
        $total = $model->where($where)->sum("amount");
        //提现申请
        $withdraw[0] = model("withdraw")->field("status,sum(money) amount,count(*) num")->where(['status' => 0])->group("status")->find();
        $withdraw[1] = model("withdraw")->field("status,sum(money) amount,count(*) num")->where(['status' => 1])->group("status")->find();
        //通道订单
        $d = "`create_time` BETWEEN " . strtotime(date("Y-m-d")) . " AND " . strtotime(date("Y-m-d", strtotime("+1 day")));
        $passageway = model('passageway')->alias('p')->join([
            ['user_passageway up', 'up.passageway_id = p.id', 'left'],
        ])->where([
            'p.status' => 1
        ])->field([
            "(SELECT COUNT(*) AS tp_count FROM `ss_order` WHERE `status` = 1 AND `user_passageway_id` = up.id AND " . $d . " LIMIT 1)" => 'num',
            "IFNULL((SELECT SUM(amount) AS tp_sum FROM `ss_order` WHERE `status` = 1 AND `user_passageway_id` = up.id AND " . $d . " LIMIT 1),0.00)" => 'amount',
            "p.name"
        ])->group("p.id")->select();
        $passageway = Db::name("passageway")->alias("p")->join([
            ['user_passageway up','up.passageway_id = p.id','left']
        ])->field( "p.name,group_concat(up.id) upid" )->where( "p.status", 1 )->group("p.id")->select();
        foreach ( $passageway as $key => $value ){
            $upid = $value['upid'];
            $t = Db::name("order")->field("count(*) num,IFNULL( sum(amount), 0.00) amount")->where(['user_passageway_id'=>['in', $upid]])->whereTime('create_time', 'today')->find();
            $passageway[$key] = array_merge( $value, $t);
        }
        $list = [
            'total' => $total,
            'withdraw' => $withdraw,
            'passageway' => $passageway
        ];
        $this->assign("list", $list);

//        //获取订单概况
//        $field = "status,count(*) total,sum(amount) amount";
//        //今日订单
//        $t = model('order')->field( $field )->whereTime( "create_time", 'today')->group("status")->select();
//        $order['t'] = $this->formatData( $t );
//        //昨日订单
//        $y = model('order')->field( $field )->whereTime( "create_time", 'yesterday')->group("status")->select();
//        $order['y'] = $this->formatData( $y );
//        //总订单
//        $a = model('order')->field( $field )->group("status")->select();
//        $order['a'] = $this->formatData( $a );
//        $this->assign( 'order', $order);
        return $this->fetch();
    }

    /**
     * @desc 格式话订单数据
     * Created by PhpStorm
     * User: zhaolan
     * Date: 2019/7/9 0009 11:05
     * @param $t
     * @return array
     */
    private function formatData($t)
    {
        $r = ['total' => 0, 'finish' => 0, 'unFinish' => 0, 'amount' => 0, 'unAmount' => 0];
        if (is_array($t)) {
            foreach ($t as $key => $val) {
                $r['total'] += $val['total'];
                if ($val['status'] == "成功") {
                    $r['finish'] = $val['total'];
                    $r['amount'] = $val['amount'];
                } else {
                    $r['unFinish'] += $val['total'];
                    $r['unAmount'] += $val['amount'];
                }
            }
        }
        return $r;
    }

    public function ajax_order()
    {

        $model = model("order");
        $m = time() - time() % 5;
        $time = [
            date("H:i:s", $m - 30),
            date("H:i:s", $m - 25),
            date("H:i:s", $m - 20),
            date("H:i:s", $m - 15),
            date("H:i:s", $m - 10),
            date("H:i:s", $m - 5),
            date("H:i:s", $m),
        ];
        $where = [
            'status' => 1
        ];
        $comp = $total = [];
        foreach ($time as $key => $val) {
            $comp[] = $model->where($where)->whereTime("create_time", '<', $val)->whereTime("create_time", "d")->sum("amount");
            $total[] = $model->whereTime("create_time", '<', $val)->whereTime("create_time", "d")->sum("amount");
        }
        $data['time'] = $time;
        $data['b'] = $comp;
        $data['a'] = $total;
        $this->success("数据获取成功！", "", $data);
    }

    public function suggest()
    {
        if ($this->request->isPost() && $this->request->isAjax()) {
            $page = $this->request->param('page', 1, 'intval');
            $per = $this->request->param('limit', 10, 'intval');
            $this->order = $this->request->param('order', $this->order);
            $where = [];
            $param = $this->request->param();
            foreach ($param as $key => $val) {
                $ps = substr($key, 0, 2);
                $vl = substr($key, 2);
                switch ($ps) {
                    case 'l-':
                        $st = substr($vl, 0, 2);
                        if ($st == "b-") {
                            $vl = substr($vl, 2);
                            if (!empty($val)) $where["b." . $vl] = ['like', '%' . $val . '%'];
                        } else {
                            if (!empty($val)) $where["a." . $vl] = ['like', '%' . $val . '%'];
                        }
                        break;
                    case 'e-':
                        $st = substr($vl, 0, 2);
                        if ($st == "b-") {
                            $vl = substr($vl, 2);
                            if (!is_null($val)) $where["b." . $vl] = $val;
                        } else {
                            if (!is_null($val)) $where["a." . $vl] = $val;
                        }
                        break;
                    case 'i-':
                        $st = substr($vl, 0, 2);
                        if ($st == "b-") {
                            $vl = substr($vl, 2);
                            if (!empty($val)) $where["b." . $vl] = ['in', $val];
                        } else {
                            if (!empty($val)) $where["a." . $vl] = ['in', $val];
                        }
                        break;
                    default:
                        break;
                }
            }
            $stime = $this->request->param('stime', 0);
            $ltime = $this->request->param('ltime', 0);
            if (empty($stime) && !empty($ltime)) {
                $ltime = strtotime($ltime);
                $where['a.create_time'] = ['<=', $ltime];
            }
            if (!empty($stime) && empty($ltime)) {
                $stime = strtotime($stime);
                $where['a.create_time'] = ['>', $stime];
            }
            if (!empty($stime) && !empty($ltime)) {
                $ltime = strtotime($ltime);
                $stime = strtotime($stime);
                $where['a.create_time'] = ['between', [$stime, $ltime]];
            }
            $page = $page - 1;
            $this->model = model('suggest');
            if (count($this->join) > 0) {
                $list = $this->model
                    ->alias('a')
                    ->join($this->join)
                    ->field($this->field)
                    ->where($where)
                    ->limit($page * $per, $per)
                    ->order($this->order)
                    ->select();
                $sql = $this->model->getLastSql();
                $count = $this->model->alias('a')->join($this->join)->where($where)->count();
            } else {
                $list = $this->model
                    ->alias('a')
                    ->field($this->field)
                    ->where($where)
                    ->limit($page * $per, $per)
                    ->order($this->order)
                    ->select();
                $sql = $this->model->getLastSql();
                $count = $this->model->alias('a')->where($where)->count();
            }
            $data = [
                'list' => $list,
                'count' => $count,
                'sql' => $sql
            ];
            $this->success('获取成功！', '', $data);
        }
        return $this->fetch();
    }


    public function check_status()
    {
        $id = $this->request->param('id', 0, 'intval');
        if (empty($id)) {
            $this->error('参数错误');
        }
        $where = [
            'id' => $id
        ];
        $this->model = model('suggest');
        $field = 'id,status,check_desc';
        $data = $this->model->where($where)->field($field)->find();
        if (request()->isPost()) {
            $post_data = $this->request->param('');
            if (!isset($post_data['status'])) {
                $this->error('请选择审核状态');
            }
            $result = $this->model->allowField(true)->save($post_data, ['id' => $id]);
            if ($result) {
                //todo: email?
                $this->success("设置成功！");
            }
            $this->error("请稍后再试！");
        }
        $this->assign('data', $data);
        return $this->fetch();
    }
}