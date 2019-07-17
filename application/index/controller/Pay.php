<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/26 0026
 * Time: 9:39
 */

namespace app\index\controller;

use app\index\model\Passageway;
use app\index\model\UserPassageway;
use daxiangpay\daxiangpay;
use think\Controller;
use think\Db;
use think\Request;
use think\Validate;

class Pay extends Controller
{
    protected $business;

    protected function _initialize()
    {
        parent::_initialize(); // TODO: del $this->business default
        $this->business = session('business')['id'];
    }

    public function index()
    {
        $passageway = new Passageway();
        $user_passageway = new UserPassageway();
        $request = $this->request;
        // todo: 检查商户信息
        if ($request->isAjax() && $request->isPost()) {
            $this->verify($request, 'stage1');
            if (!$up = $user_passageway->in($request->param('passageway'), $this->business )) {
                $this->error('该通道已被禁用，请联系网站管理员！');
            }
            $data = $request->param();
            $data['user_passageway_id'] = $up['id'];
            $res = $this->create_order($data);
            if( $res ) {
                $order = model('Order')->where('order_id',$data['order_id'])->find();
                $moneys = Pay::getArrivalPrice($order);
                model('Order')
                    ->where('order_id',$data['order_id'])
                    ->update(['rate_price'=>$moneys['ratePrice'],'arrival_price'=>$moneys['arrivalPrice']]);
                $this->success('成功', url('pay',array( "id"=> $order['id'] )));
            }
            $this->error('系统繁忙，请稍后再试！');
        } else {
            $this->assign('way', $passageway->getList());
            $this->assign('name', session('business')['name']);
            return $this->fetch();
        }
    }

    public function unionpay()
    {
        $request = request();
        $data = [
            'money' => $request->param('amount'),
            'bankname' => $request->param('banks'),
            'bankcardid' => $request->param('bank_code'),
            'bankfullname' => $request->param('name'),
            'bankidc' => $request->param('idCard'),
            'bankmobile' => $request->param('mobile'),
            'type' => $request->param('passageway'),
            'screen' => ismobile() ? 2 : 1,
            'order_id' => $request->param('order_id'),
        ];
        $api = new daxiangpay();
        $api->pay($data);
    }

    public function pay()
    {
        $request = $this->request;
        $business = $this->business;
        $data = model('order')->find($request->param('id',0,'intval'));
        if( empty( $data ) ) $this->error( "订单参数错误！");
//        $this->verify($request, 'stage2');
        if ($request->isAjax() && $request->isPost()) {

        } else{
            $moneys = $this->getArrivalPrice($data);
            $this->assign( 'moneys', $moneys);
            $this->assign( 'order', $data);

            $where = [
                'id'    => $data['user_passageway_id'],
                'status'=> 1,
                'business_id'=>$business,
            ];
            $passageway = model( 'UserPassageway')
                ->where('status',1)
                ->where( $where )
                ->value( "passageway_id");
            $this->assign("way",model('passageway')->where('status',1)->find( $passageway ));
            switch ( $passageway ) {
                case 1://支付宝
                    $api = new \app\manage\controller\Api();
                    $res = $api->Face($data);
                    $this->assign("data", $res);
                    return $this->fetch();
                    break;
                case 9://微信支付
                    return $this->fetch();
                    break;
                case 10://银联支付
                    $banks = Db::name( "banks")->where( "status = 1 and delete_time = 0 " )->select();
                    $this->assign( "banks", $banks);
                    return $this->fetch();
                    break;
                case 11://微信免签
                    $data['type'] = "1";
                    $api = new Api();
                    $res = $api->free_pay($data);
                    $res['name'] = "微信";
                    $this->assign("data", $res);
                    return $this->fetch();
                    break;
                case 12://支付宝免签
                    $data['type'] = "2";
                    $api = new Api();
                    $res = $api->free_pay($data);
                    $res['name'] = "支付宝";
                    $this->assign("data", $res);
                    return $this->fetch();
                    break;
                default:
                    $this->error('该通道已被禁用，请联系网站管理员！');
            }
        }
    }

    /**
     * 2019/7/15 0015 11:34
     * @param $order
     * @return array
     * 实际到账金额
     */
    public static function getArrivalPrice($order){

        $amount = $order['amount'];//金额

        $rate = db('passageway p')->join('user_passageway up','up.passageway_id = p.id')
            ->where('up.id',$order['user_passageway_id'])
            ->where('up.business_id',$order['business_id'])
            ->value('p.rate');

        if($rate>0){
            $ratePrice = $amount*$rate;//平台金额
            $arrivalPrice = $amount-$ratePrice;
            $data = [
                'ratePrice'=>$ratePrice,
                'arrivalPrice'=>$arrivalPrice
            ];
            return $data;
        }
        $data = [
            'ratePrice'=>0,
            'arrivalPrice'=>$amount
        ];
        return $data;
    }
    public function spage()
    {
        return $this->fetch();
    }

    public function create_order($data)
    {
        $str = [
            'business_id' => $this->business,
            'order_id' => $data['order_id'],
            'out_trade_no' => $data['order_id'],
            'pay_from' => 1,
            'create_time' => time(),
            'user_passageway_id' => $data['user_passageway_id'],
            'amount' => $data['amount'],
            'status' => 3,
            'title' => session("business")['name'],
            'back_status' => 0,
        ];
        if ($order = Db('order')->insert($str)) {
            return $order;
        } else {
            return false;
        }
    }


    public function verify(Request $request, $stage)
    {
        $type = $request->param('passageway');
        $rule = [
            'amount|支付金额' => 'require|gt:0|number',
            'passageway|支付方式' => 'require|integer',
        ];
        $msg = [
            'passageway.require' => '请选择支付方式！'
        ];
        if ($type == 10) {
            $rule['bank_type|银行名称'] = 'require';
            $rule['bank_code|银行卡号'] = 'require|min:12';
            $rule['name|开户人名称'] = 'require';
            $rule['mobile|银行预留手机号'] = 'require|min:11|max:11';
            $rule['idCard|身份证号码'] = 'require|min:15|max:18';
        }

        $validate = new Validate($rule, $msg);
        $validate->scene('stage1', ['amount', 'passageway']);
        $validate->scene('stage2', ['amount', 'passageway', 'bank_type', 'bank_code', 'name', 'mobile', 'idCard']);
        $result = $validate->scene($stage)->check($request->param());
        if (!$result) {
            $this->error($validate->getError());
        }
        if ($type == 10 && $stage != 'stage1') {
            if (!preg_match_all("/^1[34578]\d{9}$/", $request->param("mobile"), $mobiles)) {
                $this->error('银行预留手机号规则错误！');
            }
//            if(!preg_match('/^([1-9]{1})(\d{14}|\d{18})$/', $request->post("bank_code"),$match)){
//                $this->error('银行卡号规则错误！');
//            }
            if (!preg_match('/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/', trim($request->param("idCard")), $match)) {
                $this->error('身份证号输入不合法！');
            }
        }
        return $result;
    }

    /**
     * 2019/6/28 0028 10:31
     * @return mixed
     * 支付宝回调
     */
    public function aliPayNotify()
    {
        $api = new \app\manage\controller\Api();
        return $api->succNotifyServer();
    }

    public function freeList()
    {
        if ($this->request->isPost() && $this->request->isAjax()) {
            $id = $this->request->param('id', 0, 'intval');
            if (empty($id)) $this->error("参数错误！");
            $where = ['id' => $id, 'is_free' => 0];
            $type = db('passageway')->where($where)->value('pay_type');
            if (!$type) $this->error("该支付方式不存在或没有变化！");
            $business_id = $this->business;
            $where = [
                'business_id' => $business_id,
                'delete_time' => 0,
                'status' => 1,
                'passageway_id' => $id
            ];
            $n = db('user_passageway')->where($where)->count();
            if ($n < 1) $this->error("该商户未开启此通道！");
            switch ($type) {
                case "free_wechat":
                    $where = ['type' => 1];
                    break;
                case "free_alipay":
                    $where = ['type' => 2];
                    break;
                default:
                    $this->error("未定义支付方式！");
                    break;
            }
            $list = db('qrcode')->where($where)->select();
            $str = "<input type='radio' title='不定额测试' lay-filter='randM'  name='mt' checked value='0'>";
            foreach ($list as $key => $val) {
                $str .= "<input type='radio' data-price='" . $val['price'] . "' title='定额测试(&yen;" . $val['price'] . ")' name='mt' lay-filter='randM' value='" . $val['id'] . "'>";
            }
            $this->success($str);
        }
        $this->error("请求方式错误");
    }

    public function orderStatus()
    {
        $Ikey = $this->request->param("Ikey");
        if ($Ikey) {
            $order = db('order')->where('out_trade_no', $this->request->param('Ikey'))->find();
            if ($order) {
                if ($order['status'] == 1) {
                    $amount = $order['amount'];
                    do{
                        $api = new Api();
                        $api->audio( "收钱包到账".$amount."元", $amount);
                    }while( !file_exists( "MP3/".$amount."_audio.mp3") );

                    $this->success("成功！", "","MP3/".$amount."_audio.mp3");
                }
                $this->error("失败！");
            }
        }
        $this->fetch();
    }

}