<?php

namespace app\manage\controller;

use app\common\controller\Curl;
use app\common\controller\Manage;
use think\Controller;

class Payment extends Controller//todo:extends manage
{
    public function testadd()
    {
        $res = Curl::post('http://www.f44.com/manage/payment/addPayment',
            ['user_name'=>'胡译锋',
                'order_goods_code'=>'6217003810031856423',
                'order_Opening_address'=>'0105',
                'order_goods'=>1,
                'order_joint_number'=>'0105',
                'bank_id'=>'0105',
                'order_passageway_id'=>1,
                'order_isservice_item'=>1]);
        echo $res;
    }

    public function addPayment() // 添加代付
    {
        $user_name = input('post.user_name');   // 收款人
        $order_goods_code = input('post.order_goods_code');   // 银行卡号
        $order_Opening_address = input('post.order_Opening_address');   // 开户行
        $order_goods = input('post.order_goods');   // 金额
        $order_joint_number = input('post.order_joint_number');   // 联行号
        $order_remarks = input('post.order_remarks');   // 备注
        $order_province = input('post.order_province');   // 省
        $order_city = input('post.order_city');   // 市
        $bank_id = input('post.bank_id');   // 银行编码
        $order_passageway_id = input('post.order_passageway_id');   // 通道id
        $order_isservice_item = input('post.order_isservice_item');   // 扣款项 1(扣用户) 2（扣公司）

        $data = [
            'user_name' => $user_name,
            'order_goods_code' => $order_goods_code,
            'order_Opening_address' => $order_Opening_address,
            'order_goods' => $order_goods,
            'bank_id' => $bank_id,
            'order_isservice_item' => $order_isservice_item,
            'order_passageway_id' => $order_passageway_id
        ];
        $validate = new \think\Validate([  //  验证数据
            ['user_name', 'require', '收款人不能为空'],
            ['order_goods_code', 'require', '银行卡号'],
            ['order_goods_code', 'number', '银行卡号格式不正确'],
            ['order_Opening_address', 'require', '开户行id不能为空'],
            ['order_goods', 'require', '金额不能为空'],
            ['bank_id', 'require', '银联编码不能为空'],
            ['order_isservice_item', 'require', '扣款项不能为空'],
            ['order_passageway_id', 'require', '通道id不能为空'],
        ]);
        if (!$validate->check($data)) {
            return json(['data' => [], 'code' => '0', 'msg' => $validate->getError()]);
        }
        if (strlen($order_goods_code) < 15) return json(['data' => [], 'code' => '0', 'msg' => '请输入正确的卡号']);
        $AdministrationUserM = new AdministrationUserM();
        $sess_user = Session::get('username');
        $user_where = ['a.id' => $sess_user['id']];
        $user_ageway = $this->orderM->user_passageway('zs_user', $user_where);
        if (!$user_ageway) {
            return json(['data' => [], 'code' => '0', 'msg' => '操作人id错误']);
        } else {
            Db::startTrans(); // 开启事务
            try {
                //加锁
                $this->filopen();
                if (flock(self::$filopenin, LOCK_EX | LOCK_NB)) {
                    //TODO 执行业务代码
                    $company_id = $user_ageway[0]['user_companyId'];        //  公司id
                    $company_where = [
                        'company_id' => $company_id,
                    ];
                    $company = $AdministrationUserM->selec('zs_company', $company_where); // 查询公司余额
                    if ($company[0]['company_state'] != 1) return json(['data' => [], 'code' => '0', 'msg' => '当前公司被冻结']);
                    $order_single_service = $user_ageway[0]['single_cost'];  //单笔手续费
                    $order_service = $user_ageway[0]['cost'] * $order_goods;  //费率
                    $procedures = $order_service < $order_single_service ? $order_single_service : $order_service; // 总收费
                    if ($order_goods < $procedures) return json(['data' => [], 'code' => '0', 'msg' => '打款金额小于手续费']);
                    if ($order_isservice_item == 1) // 计算打款金额
                    {
                        $push_money = $order_goods - $procedures; // 打款金额
                        $company_money = round($company[0]['company_quota'] - $order_goods, 2);  // 公司余额
                    } else {
                        $push_money = $order_goods;  // 打款金额
                        $company_money = round($company[0]['company_quota'] - $push_money - $procedures, 2);  // 公司余额
                    }
                    if ($company_money < 0) return json(['data' => [], 'code' => '0', 'msg' => '余额不足']);
                    if (intval($push_money * self::HUNDRE) < 1) return json(['data' => [], 'code' => '0', 'msg' => '打款金额最小单位为分']);
                    $cpmpany_str = [
                        'company_quota' => $company_money,
                        'company_update_time' => date('Y-m-d H:i:s'),
                    ];
                    $AdministrationUserM->modify('company', $company_where, $cpmpany_str);
                    $inserData = [
                        'order_code' => $this->generate16Num(),
                        'order_payment_code' => '',
                        'order_userId' => $sess_user['id'],
                        'order_receivables' => $user_name,
                        'order_goods_code' => $order_goods_code,
                        'order_goods' => $push_money,
                        'order_passageway_id' => $order_passageway_id,
                        'order_company_id' => $company_id,
                        'order_remarks' => $order_remarks,
                        'order_feedback' => 0,
                        'order_creat_time' => time(),
                        'order_state' => 3,
                        'order_service' => $order_service,
                        'order_single_service' => $order_single_service,
                        'order_Opening_address' => $order_Opening_address,
                        'order_joint_number' => $order_joint_number,
                        'order_isservice_item' => $order_isservice_item,
                        'order_province' => $order_province,
                        'order_city' => $order_city,
                        'order_company_money' => $company_money,
                    ];
                    $this->orderM->addCompany('order', $inserData);
                    Db::commit();
                    $this->folfile(); // 解锁
                    return json(['data' => [], 'code' => '1', 'msg' => '提交成功']);
                } else {
                    $this->folfile(); // 解锁
                    return json(['data' => [], 'code' => '0', 'msg' => '系统繁忙']);
                }
            } catch (\Exception $e) {
                $this->folfile(); // 解锁
                Db::rollback();
                return json(['data' => [], 'code' => '0', 'msg' => '提交失败']);
            }
        }
    }
}