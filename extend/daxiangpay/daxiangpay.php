<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/28 0028
 * Time: 15:31
 */

namespace daxiangpay;

class daxiangpay
{

    protected $config;
    protected $response;
    protected $responseArray;
    protected $respCode;
    protected $respMsg;

    public function __construct()
    {
        $this->config = config('daxiangpay');
    }

    public function pay($data, $tag=true)
    {
        //组织发起支付所需要的数据
        $payInfo = array(
            'version' => 'v1.0',
            'merid' => $this->config['CC_PAY_MERID'],    //商户号
            'paytypekey' => $this->config['CC_PAY_PAY_TYPE_KEY'],    //通道类型
            'orderid' => $data['order_id'],    //订单号
            'amount' => number_format($data['money'], 2, '.', ''),    //支付金额，必须格式化为小数点后2位。例如1元，应当填写为 1.00
            'callbackurl' => $this->config['callbackurl'],     //支付后，浏览器跳转回来所要到达的页面
            's2surl' => $this->config['s2surl'],     //支付成功后，接收 支付服务器通知支付结果情况 的页面
        );

        //根据支付方式，增加其它相应的数据
        switch ($this->config['CC_PAY_PAY_TYPE_KEY']) {
            case 'ibank':
                //网银支付，需要指定直连的银行名称
                //即使指定银行名称，也不一定能直连，有可能到达收银台去选择银行，这个视渠道而定
                $payInfo['bankname'] = $data['bankname'];
                break;
            case 'fast':
                //快捷支付
                $payInfo['bankname'] = $data['bankname'];    //银行名称
                $payInfo['bankcardid'] = $data['bankcardid'];    //银行卡号
                $payInfo['bankfullname'] = $data['bankfullname'];    //姓名
                $payInfo['bankidc'] = $data['bankidc'];    //身份证号
                $payInfo['bankmobile'] = $data['bankmobile'];    //手机号
                $payInfo['screen'] = $data['screen'];    //1=PC版（默认），2=手机版
                break;
            case 'scan':
                //扫码支付
                break;
        }

        $payInfo = $this->encryption($payInfo);
        $tag&&$this->post($payInfo);
    }

    protected function encryption($payInfo): array
    {
        //1、按key先排序
        ksort($payInfo);
        reset($payInfo);
//2、把值串接一起
        $payInfo2Str = implode('', $payInfo);
//3、再串上API密钥
        $payInfo2Str .= $this->config['CC_PAY_API_KEY'];
//4、进行32位的md5加密，结果即是签名
        $sign = md5($payInfo2Str);
//5、签名要转换为小写字母的字符串（md5加密结果本身就是小写字母的字符串，这里不管，执行转换为小写，因为它是一个处理逻辑，适用于其它任意语言，如.NET、JAVA）
        $sign = strtolower($sign);

//把签名加进发起支付的数据里
        $payInfo['sign'] = $sign;

//预留参数，目前请保持为空
        $payInfo['oparms'] = '';

//附加数据，该数据在支付回发通知时，原样返回（回发post到s2surl）
//请按实际需要填写，可以为空
//若数据内容不为空，数据内容必须用base64编码；回发回来的时候，也是base64编码，请自行解码
        $payInfo['attach'] = base64_encode($payInfo['orderid']);

        return $payInfo;
    }

    protected function post($payInfo)
    {
        $EOF = <<<EOF
        <!DOCTYPE html>
        <html>
        <head>
        <meta name="viewport" content="initial-scale=1.0,user-scalable=no,maximum-scale=1,width=device-width">
        <meta name="viewport" media="(device-height: 568px)" content="initial-scale=1.0,user-scalable=no,maximum-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>支付发起中..</title>
        </head>
        <body>
        支付发起中..
        <form method="post" action="
EOF;
        echo $EOF;
        echo $this->config['CC_PAY_POST_URL'];
        echo '" id="payForm" name="payForm">';
        foreach ($payInfo as $key => $val) {
            echo '<input type="hidden" name="', $key, '" value="', $val, '">';
        }
        $EOF = <<<EOF
</form>
<script type="text/javascript">
    document.getElementById('payForm').submit();
</script>
</body>
</html>
EOF;
        echo $EOF;
    }

    public function signChk($paidInfo)
    {
        $sign = $paidInfo['sign'];
        unset($paidInfo['sign']);
        ksort($paidInfo);
        reset($paidInfo);
        $paidInfo2Str = implode('',$paidInfo);
        $paidInfo2Str .= $this->config['CC_PAY_API_KEY'];
        $signChk = md5($paidInfo2Str);
        $signChk = strtolower($signChk);

        if($sign == $signChk){
            return $paidInfo;
        }else{
            return false;
        }
    }
}