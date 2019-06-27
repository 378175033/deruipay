<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/27 0027
 * Time: 13:08
 */

namespace app\business\model;


use think\Model;

class Withdraw extends Model
{
    protected $autoWriteTimestamp = true;

    /**
     * 2019/6/27 0027 14:09
     * @desc 检验新增数据
     * @ApiParams
     * @ApiReturnParams
     * @param $param
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @return array
     */
    public function checkData( $param )
    {
        $money = model( 'Business')->where( 'id',$param['bus_id'])->value( 'money');
        //校验申请提现金额
        if( $param['money'] > $money ){
            return msg( "申请提现的最大金额不能超出".$money );
        }
        //获取商户提现账户信息
        $where = [
            'business_id'   => $param['bus_id']
        ];
        $data = model('account')->where( $where )->find();
        if( !$data ){
            $data = $where;
            model('account')->allowField( true )->isUpdate( false )->save( $data );
            $data['id'] = model('account')->id;
            return msg( "请前往管理收款账号!");
        }
        switch ( $param['w_id'] ){
            case 1:
                if( empty( $data['ali_name'] ) || empty( $data['ali_user']) ) return msg( "请前往完善支付宝收款账号信息!");
                break;
            case 2:
                if( empty( $data['we_name'] ) || empty( $data['we_user']) ) return msg( "请前往完善微信收款账号信息!");
                break;
            case 3:
                if( empty( $data['un_name'] ) || empty( $data['un_user']) || empty( $data['un_branch']) || empty( $data['un_bank']) ) return msg( "请前往完善银行卡收款账号信息!");
                break;
            default:
                return msg( "未知收款账户!");
                break;
        }
        return msg( $money-$param['money'],1);
    }

    /**
     * 2019/6/27 0027 14:46
     * @desc 新增提现申请
     * @ApiParams
     * @ApiReturnParams
     * @param $param array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @return array
     */
    public function doInsert( $param )
    {
        $res = $this->checkData( $param );
        if( empty( $res['status'] ) ){
            return $res;
        }
        //写入申请提现
        $result = $this->allowField( true )->data( $param )->isUpdate( false )->save();
        if( $result ){
            $res = model( 'Business')->changeMoney( -$param['money'], $res['msg'], $param['bus_id'], 3);
            if( $res ){
                return msg( "申请成功！",1);
            } else {
                return msg( "新增失败！");
            }
        }
        return msg( "申请失败");
    }
}