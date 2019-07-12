<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/14 0014
 * Time: 13:19
 */

namespace app\manage\controller;
use app\common\controller\Manage;

/**
 * @desc 后端首页
 * Class Index
 * @package app\manage\controller
 */
class Index extends Manage
{
    public function index()
    {
        $this->assign( 'menu', model( 'menu')->treeMenu() );
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
        $model = model( "order");
        $where = ['status'=>1];
        //总入金
        $total = $model->where( $where)->sum( "amount");
        //提现申请
        $withdraw[0] = model("withdraw")->field( "status,sum(money) amount,count(*) num")->where(['status'=>0])->group( "status")->find();
        $withdraw[1] = model("withdraw")->field( "status,sum(money) amount,count(*) num")->where(['status'=>1])->group( "status")->find();
        //通道订单
        $d =  "`create_time` BETWEEN ".strtotime( date("Y-m-d") )." AND ".strtotime( date("Y-m-d",strtotime("+1 day")) );
        $passageway = model('passageway')->alias('p')->join([
            ['user_passageway up','up.passageway_id = p.id','left'],
        ])->where([
            'p.status'=>1
        ])->field([
            "(SELECT COUNT(*) AS tp_count FROM `ss_order` WHERE `status` = 1 AND `user_passageway_id` = up.id AND ".$d." LIMIT 1)" =>'num',
            "IFNULL((SELECT SUM(amount) AS tp_sum FROM `ss_order` WHERE `status` = 1 AND `user_passageway_id` = up.id AND ".$d." LIMIT 1),0.00)" =>'amount',
            "p.name"
        ])->group("p.id")->select();
//        echo $model->getLastSql();
        $list = [
            'total' => $total,
            'withdraw' => $withdraw,
            'passageway'   => $passageway
        ];
        $this->assign( "list",$list );

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
    private function formatData( $t )
    {
        $r = ['total'=>0,'finish'=>0,'unFinish'=>0,'amount'=>0,'unAmount'=>0];
        if( is_array( $t ) ){
            foreach ( $t as $key => $val ){
                $r['total'] += $val['total'];
                if( $val['status'] == "成功" ){
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
        if( $this->request->isPost() && $this->request->isAjax() )
        {
            $type = $this->request->param('type', '');
            if( $type == "begin"){
                $m = time()-time()%5;
                $time = [
                    date( "H:i:s", $m-30),
                    date( "H:i:s", $m-25),
                    date( "H:i:s", $m-20),
                    date( "H:i:s", $m-15),
                    date( "H:i:s", $m-10),
                    date( "H:i:s", $m-5),
                    date( "H:i:s", $m),
                ];
                $field = "sum(amount) amount";
                $where = [
                    'status' => 1
                ];
                $list['finish'] = model( "order")->where( $where )->field( $field )->whereTime("create_time","-5 s")->find()['amount'];
                $list['total'] = model( "order")->where( $where )->field( 'status,count(*) total,sum(amount) amount' )->whereTime("create_time","d")->find()['amount'];
            } else {
                $time = date('-5s');
                $field = "sum(amount) amount";
                $where = [
                    'status' => 1
                ];
                $list['finish'] = model( "order")->where( $where )->field( $field )->whereTime("create_time","-5 s")->find()['amount'];
            }
            $list['sql'] = model( "order")->getLastSql();
            $data['time'] = $time;
            $data['list'] = $list;
            $this->success( "数据获取成功！", "", $data );
        }
    }
}