<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/17 0017
 * Time: 14:46
 */

namespace app\manage\controller;

use app\common\model\Notify;
use app\index\model\Verify;
use think\Controller;
use think\Log;
use app\manage\model\Business;

class Api extends Controller
{
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
    }

    public function getmenus()
    {
        if (request()->isAjax() && request()->isPost()) {
            $where = [
                'delete_time' => 0
            ];
            $menu = model('Menu')->where($where)->select();
            $return = array();
            foreach ($menu as $key => $val) {
                $return[$key]['path'] = "/" . $val['controller'] . "/" . $val['action'];
                $return[$key]['component'] = "manage/" . $val['controller'] . "/" . $val['action'];
                $return[$key]['name'] = $val['name'];
            }
            $this->success("成功获取菜单！", '', $return);
        }
    }

    /**
     * 2019/6/19 0019 10:43
     * @desc 更新用户头像信息
     * @ApiParams
     * @ApiReturnParams
     */
    public function updateUser()
    {
        if (session("?userInfo")) {
            $param = $this->request->param();
            $id = session("userInfo")['id'];
            $res = model("user")->allowField(['avatar'])->isUpdate(true, ['id' => $id])->save($param);
            if ($res) {
                operaLog('管理员' . $id . '更换头像');
                $this->success("头像上传成功！");
            } else {
                $this->error("头像上传失败！");
            }
        }
    }

    public function getMenu($current = 0)
    {
        $where = [
            'delete_time' => 0,
            'status' => 1,
            'parent_id' => 0
        ];
        $list = model('menu')->where($where)->field("id,name")->select();
        $str = "";
        if (count($list)) {
            foreach ($list as $v) {
                $cc = "";
                if ($v['id'] == $current) {
                    $cc = "selected";
                }
                $str .= "<option value='" . $v['id'] . "' $cc>|--" . $v['name'] . "</option>";
                $where['parent_id'] = $v['id'];
                $child = model('menu')->where($where)->field("id,name")->select();
                foreach ($child as $val) {
                    $cc = "";
                    if ($val['id'] == $current) {
                        $cc = "selected";
                    }
                    $str .= "<option value='" . $val['id'] . "' $cc>|--|--" . $val['name'] . "</option>";
                }
            }
        }
        return $str;
    }

    /**
     * 2019/6/5 0005 16:52
     * @desc 获取权限配置菜单列表
     * @ApiParams id 用户组ID
     * @ApiReturnParams data tree结构的菜单列表
     */
    public function getAuth()
    {
        $id = request()->param('id', 0, 'intval');
        if (empty($id)) {
            die("参数错误！");
        }
        $where = [
            'id' => $id,
            'status' => 1
        ];
        $rule = model('user')->where($where)->value('rule');
        $data = model('menu')->where(['delete_time' => 0])->field('id,parent_id pId,name')->select();
        foreach ($data as $key => $v) {
            if (in_array($v['id'], explode(',', $rule))) {
                $data[$key]['checked'] = true;
            }
        }
        $this->success($data);
    }

    /**
     * 2019/6/19 0019 17:44
     * @desc清除缓存
     * @ApiParams
     * @ApiReturnParams
     */
    public function clear()
    {
        $dir_name = ".." . DS . "/runtime";
        $this->delete_dir_file($dir_name);
        operaLog(session('userInfo')['id'] . '清除缓存');
        $this->success('清除成功', 'index/index');
    }

    /**
     * 2019/6/19 0019 17:58
     * @desc 循环删除缓存文件
     * @ApiParams
     * @ApiReturnParams
     * @param string $dir_name
     * @return bool
     */
    public function delete_dir_file($dir_name)
    {
        $result = false;
        if (is_dir($dir_name)) { //检查指定的文件是否是一个目录
            if ($handle = opendir($dir_name)) {   //打开目录读取内容
                while (false !== ($item = readdir($handle))) { //读取内容
                    if ($item != '.' && $item != '..') {
                        if (is_dir($dir_name . DS . $item)) {
                            $this->delete_dir_file($dir_name . DS . $item);
                        } else {
                            unlink($dir_name . DS . $item);  //删除文件
                        }
                    }
                }
                closedir($handle);  //打开一个目录，读取它的内容，然后关闭
                if (rmdir($dir_name)) { //删除空白目录
                    $result = true;
                }
            }
        }
        return $result;
    }

    /**
     *  商家收款 （当面付）
     * @param $data array
     */
    public function Face($data)
    {
        require_once dirname(dirname(dirname(dirname(__FILE__)))) . "/extend/alipay/f2fpay/model/builder/AlipayTradePrecreateContentBuilder.php";
        require_once dirname(dirname(dirname(dirname(__FILE__)))) . "/extend/alipay/f2fpay/service/AlipayTradeService.php";
        require_once dirname(dirname(dirname(dirname(__FILE__)))) . "/extend/alipay/f2fpay/qrpay_test.php";
        $orderTitel = $data['title'];
        $goods = $data['amount'];
        $outTradeNo = $data['order_id'];
        $succ = pay_face($outTradeNo, $orderTitel, $goods, $config);
        if (ismobile()) {
            $succ = "<script> window.location.href='" . $succ['url'] . "'</script>";
        } else {
            $succ = $succ['code'];
        }
        return $succ;
        $this->success("获取二维码成功！", '', $succ);
//        if ($succ != 1 && $succ != 3) {
//            $this->success('支付宝创建订单二维码成功', '', $succ);
//        } elseif ($succ == 1) {
//            $this->error('支付宝创建订单二维码失败', '');
//        } else {
//            $this->error('系统异常，状态未知!!', '');
//        }
    }

    /**
     * 支付宝回调
     * */
    public function succNotifyServer($param)
    {
        if ($param['trade_status'] == 'TRADE_FINISHED' || $param['trade_status'] == 'TRADE_SUCCESS') {
            // 处理支付成功后的逻辑业务
            $order = db('order')->where(['order_id' => $param['out_trade_no']])->find();
            if (!$order) {
                Log::error('order not exists');
                return 'order not exists';
            }
            //订单状态错误 1 未付款 其他状态均为已处理的状态
            if ($order['status'] != 3) {
                Log::error('order is completed:' . $order['status']);
                return true;
            }
            if ($order['amount'] != $param['total_amount']) {
                Log::error('total_amount is error:' . $order['amount'] . ',' . $param['total_amount']);
                return 'total_amount is error';
            }
            $transaction_id = $param['trade_no'];
            $seller_email = $param['seller_email'];      //	卖家支付宝账号
            $notify_time = $param['notify_time'];         // 通知时间
            $receipt_amount = $param['receipt_amount'];  //实收金额
            $buyer_logon_id = $param['buyer_logon_id'];  //买家支付宝账号
            $order['batch'] = $transaction_id;// 支付宝交易号（流水号）
            $order['amount'] = $receipt_amount;
            $order['update_time'] = strtotime($notify_time);
            $order['back_time'] = strtotime($notify_time);
            $order['status'] = 1;//支付状态
            $order['back_status'] = 1;//回调状态
            $order['back_info'] = '收款方' . $seller_email . ',付款方' . $buyer_logon_id;//回调参数

            //修改订单信息
            db('order')->where(['order_id' => $param['out_trade_no']])->update($order);
            //支付成功的逻辑
            $this->accountLog($order);
            if($order['order_sn']) {//如果是第三方的订单才做回调处理
                $Verify = new Verify();
                $Verify->verifyNotify($order, $order['business_id']);//回调验证
            }
            return 'success';

        } else {
            $this->error('支付失败');
        }
    }

    /**
     * 2019/6/28 0028 16:52
     * @param $order
     * 回调成功的时候金额变动
     */
    public function accountLog($order)
    {

        $accountLog = db('account_log')->where('bus_id', $order['business_id'])->order('id desc')->find();
        $Business = new Business();
        $Pay = new \app\index\controller\Pay();
        $data = $Pay->getArrivalPrice($order);
        $order['amount'] = $data['arrivalPrice'];
        $Business->changeMoney($order['amount'], $accountLog['now_account'] + $order['amount'], $order['business_id'], 0);
    }

    public function testNotify()
    {
        $data = ['url' => 'baidu',
            'screct_key' => '1234567891011121'];
        $a = openssl_encrypt(json_encode($data), 'aes-256-ofb', '1212', OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, '1212121212121212');
        echo $a;
        $decrypted = openssl_decrypt($a, 'aes-256-ofb', '1212', OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, '1212121212121212');
        halt($decrypted);
        $this->notify($data);
    }

    // 通知商户     $screct_key 至少16位
    public function notify($data)
    {
        $url = $data['url'];
        $screct_key = $data['screct_key'];
        $data = json_encode($data);
        $data = encode($data, base64_encode($screct_key));
        echo($data);
        halt(decode($data, base64_encode($screct_key)));
        $headerArray = array("Content-type:application/json;charset='utf-8'", "Accept:application/json");
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headerArray);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return json_decode($output, true);
    }
}