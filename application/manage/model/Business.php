<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/18 0018
 * Time: 14:49
 */

namespace app\manage\model;
use think\Model;
use think\Db;
class Business extends Model
{
    protected $autoWriteTimestamp = true;
    protected function getCheckTimeAttr( $value )
    {
        return date("Y-m-d H:i:s", $value);
    }
    protected function getLastLoginTimeAttr( $value )
    {
        return date( 'Y-m-d H:i:s', $value);
    }

    /**
     * 2019/6/28 0028 16:48
     * @param $money 金额
     * @param $now_money 余额
     * @param $id 商户id
     * @param int $type 支付类型
     * @return bool
     * 金额变动
     */
    public function changeMoney( $money, $now_money , $id ,$type = 0  )
    {
        $con = [
            'money' => $now_money
        ];
        Db::startTrans();
        try{
            $where = ['id'=> $id];
            //设置商户余额
            Db::name('Business')->where( $where )->update( $con );
            $data = [
                'account'   => $money,
                'bus_id'    => $id,
                'now_account'   => $now_money,
                'info'      => $type,
                'create_time'   => time()
            ];
            //写入商户余额日志
            Db::name( 'AccountLog')->insert( $data );
            return true;
        }catch ( \Exception $e){
            Db::rollback();
            return false;
        }
    }

    // 查询可用金额
    public function avalible_money()
    {

    }
}