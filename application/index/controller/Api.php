<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/20 0020
 * Time: 17:43
 */

namespace app\index\controller;
use think\Controller;

class Api extends Controller
{
    public function index()
    {
        //获取商户ID
        $id = request()->param( 'id', 0, 'intval');
        if( empty( $id ) ){
            $this->error( "参数信息错误！");
        }
        //获取扫码方式
        $type = user_agent();
        echo "<h1>这是ID为".$id."的商户来自".$type."的扫码</h1>";
    }

    public function pay()
    {
        if( $this->request->isPost() ){
            $data = [];
            $type = $this->request->param( 'type', '');
            if( empty( $type ) ){
                $this->error( "请选择支付方式！");
            }
            //交易金额
            $amount = $this->request->param( "amount", 0);
            if( $amount <= 0 ){
                $this->error("交易金额不合法，请重新输入！");
            }
            $data['amount'] = round( $amount, 2)*100;
            //商户号：
            $data['business_sn'] = "777290058110048";//默认商户号仅作为联调测试使用，正式上线还请使用正式申请的商户号
            //商户订单号
            $data['order_sn']   = date("YmdHis");//自行定义，8-32位数字字母
            //订单发送时间
            $data['send_time']  =  date("YmdHis");//格式为YYYYMMDDhhmmss，取北京时间
            $res = $this::$type( $data );
//            $this->success( "提交成功！",'', $res );
        }
        //获取所有的支付方式
        $payWay = [
            ['name'=>'银联支付','type'  =>'unionpay'],
            ['name'=>'支付宝支付','type'  =>'alipay'],
            ['name'=>'微信支付','type'  =>'wechat'],
//            ['name'=>'银联支付','type'  =>'unionpay']
        ];
        $this->assign( "payWay", $payWay);
        return $this->fetch();
    }

    public function doPay()
    {
        echo "支付成功额！";
    }

    /**
     * 2019/6/24 0024 10:43
     * @desc银行卡支付
     * @ApiParams
     * @ApiReturnParams
     */
    private static function unionpay( $data )
    {
        //支付配置文件
        $sdkConifg = new \pay\unionpay\SDKConfig();
        //支付服务文件
        $acpService = new \pay\unionpay\AcpService();
        $params = array(
            //以下信息非特殊情况不需要改动1
            'version' => $sdkConifg::getSDKConfig()->version,                 //版本号
            'encoding' => 'utf-8',				  //编码方式
            'txnType' => '01',				      //交易类型
            'txnSubType' => '01',				  //交易子类
            'bizType' => '000201',				  //业务类型
            'frontUrl' =>  $sdkConifg::getSDKConfig()->frontUrl,  //前台通知地址
            'backUrl' => $sdkConifg::getSDKConfig()->backUrl,	  //后台通知地址
            'signMethod' => $sdkConifg::getSDKConfig()->signMethod,	              //签名方法
            'channelType' => '08',	              //渠道类型，07-PC，08-手机
            'accessType' => '0',		          //接入类型
            'currencyCode' => '156',	          //交易币种，境内商户固定156

            //TODO 以下信息需要填写
            'merId' => $data["business_sn"],		//商户代码，请改自己的测试商户号，此处默认取demo演示页面传递的参数
            'orderId' => $data["order_sn"],	//商户订单号，8-32位数字字母，不能含“-”或“_”，此处默认取demo演示页面传递的参数，可以自行定制规则
            'txnTime' => $data["order_sn"],	//订单发送时间，格式为YYYYMMDDhhmmss，取北京时间，此处默认取demo演示页面传递的参数
            'txnAmt' => $data["amount"],	//交易金额，单位分，此处默认取demo演示页面传递的参数
            // 订单超时时间。
            // 超过此时间后，除网银交易外，其他交易银联系统会拒绝受理，提示超时。 跳转银行网银交易如果超时后交易成功，会自动退款，大约5个工作日金额返还到持卡人账户。
            // 此时间建议取支付时的北京时间加15分钟。
            // 超过超时时间调查询接口应答origRespCode不是A6或者00的就可以判断为失败。
            'payTimeout' => date('YmdHis', strtotime('+15 minutes')),
            'riskRateInfo' =>'{commodityName=测试商品名称}',
            // 请求方保留域，
            // 透传字段，查询、通知、对账文件中均会原样出现，如有需要请启用并修改自己希望透传的数据。
            // 出现部分特殊字符时可能影响解析，请按下面建议的方式填写：
            // 1. 如果能确定内容不会出现&={}[]"'等符号时，可以直接填写数据，建议的方法如下。
            //    'reqReserved' =>'透传信息1|透传信息2|透传信息3',
            // 2. 内容可能出现&={}[]"'符号时：
            // 1) 如果需要对账文件里能显示，可将字符替换成全角＆＝｛｝【】“‘字符（自己写代码，此处不演示）；
            // 2) 如果对账文件没有显示要求，可做一下base64（如下）。
            //    注意控制数据长度，实际传输的数据长度不能超过1024位。
            //    查询、通知等接口解析时使用base64_decode解base64后再对数据做后续解析。
            //    'reqReserved' => base64_encode('任意格式的信息都可以'),

            //TODO 其他特殊用法请查看 special_use_purchase.php
        );
        $acpService::sign ( $params );
        $uri = $sdkConifg::getSDKConfig()->frontTransUrl;
//        $data = Curl::post( $uri, $params );
//        var_dump( $data );
        $html_form = $acpService::createAutoFormHtml( $params, $uri );
        echo  $html_form;
    }

    public function free_pay( $data )
    {
        $type = $data['type'];
        $money = $data['money'];
        //查看是否定额支付
        $where =[
            'type'  => $type,
            'price' => round( $money, 2)
        ];
        $url = db( 'qrcode')->where( $where )->value( "pay_url");
        $vkey = $type == 1 ? 'wxpay' : 'zfbpay';
        $url = $url ? $url : db('setting')->where('vkey',$vkey)->value( 'vvalue');
        //获取二维码
        if( $url ){
            $this->success("获取二维码成功！",'', $url);
        }
        $this->error( "不存在的二维码，请前往上传！");
    }
}
