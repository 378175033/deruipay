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

    public function welcome()
    {
        //获取订单概况
        $field = "status,count(*) total,sum(amount) amount";
        //今日订单
        $t = model('order')->field( $field )->whereTime( "create_time", 'today')->group("status")->select();
        $order['t'] = $this->formatData( $t );
        //昨日订单
        $y = model('order')->field( $field )->whereTime( "create_time", 'yesterday')->group("status")->select();
        $order['y'] = $this->formatData( $y );
        //总订单
        $a = model('order')->field( $field )->group("status")->select();
        $order['a'] = $this->formatData( $a );
        $this->assign( 'order', $order);
        return $this->fetch();
    }

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
}