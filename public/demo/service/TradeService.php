<?php

require_once "Rsa.php";
require_once "Curl.php";

define('BASE_PATH',str_replace('\\','/',realpath(dirname(__FILE__).'/'))."/");
/**
 * 支付接口调用服务
 * Class TradeService
 */
class  TradeService{

    //得瑞网关地址
    public $gateway_url;

    //异步通知回调地址
    public $notify_url;

    //商户号
    public $business_id;

    //众和公钥
    public $public_key;

    //商户私钥
    public $private_key;

    //开发者密钥
    public $appSecret;

    /**
     * @var string 令牌
     */
    public $token;

    /**
     * @var string 请求头
     */
    public $http = "http";

    private $code = [
        '100'   => "请求成功！",
        '101'   => '异步请求地址错误',
        '102'   => '网关请求地址错误',
        '103'   => '请配置您的商户号',
        '104'   => '众和公钥证书地址为空',
        '105'   => '开发者密钥不能为空',
        '106'   => '商户私钥证书地址不能为空',
        '109'   => '令牌值不为空',
        '110'   => '私钥地址错误',
        '111'   => '公钥地址错误',
    ];

    public function __construct( $config )
    {
        $this->notify_url = $config['notify_url'];
        $this->gateway_url = $config['gatewayUrl'];
        $this->business_id = $config['business_id'];
        $this->public_key = $config['public_key'];
        $this->appSecret = $config['appSecret'];
        $this->private_key = $config['private_key'];
        $this->token = $config['token'];
        if( empty( trim ($this->notify_url ) ) ) return $this->back( "101");
        if( empty( trim ($this->gateway_url ) ) ) return $this->back( "102");
        if( empty( trim ($this->business_id ) ) ) return $this->back( "103");
        if( empty( trim ($this->public_key ) ) ) return $this->back( "104");
        if( empty( trim ($this->appSecret ) ) ) return $this->back( "105");
        if( empty( trim ($this->private_key ) ) ) return $this->back( "106");
        if( empty( trim ($this->token ) ) ) return $this->back( "109");
    }

    private function back( $code )
    {
        exit( $this->code[ $code ] );
    }

    //公钥加密请求
    private function enData( $data )
    {
        //获取公钥
        if( !file_exists( BASE_PATH.$this->public_key ) ){
            $this->back( 111);
        }
        $rsa = new Rsa();
        $pubkey  = file_get_contents( BASE_PATH.$this->public_key );
        $rsa->init( "", $pubkey, true);
        $sa = $rsa->pub_encode( $data );
        return $sa;
    }

    public function deData( $data ){
        if( !file_exists( BASE_PATH.$this->private_key ) ){
            $this->back( 110);
        }
        $privkey = file_get_contents( BASE_PATH.$this->private_key );
        $rsa = new Rsa();
        $rsa->init( $privkey, "", true);
        $sa = $rsa->priv_decode( $data );
        return $sa;
    }
    public function jumpPay( $order ){
        $data = [
            "api_key"  => $this->appSecret,
            "timestamp"    => time(),
            "business_id"   => $this->business_id,
            "order_sn"  => time(),
            'api_secret'   => $this->token
        ];
        $data = array_merge( $data , $order );
        $order_sn = $data['order_sn'];
        ksort($data);//先升序排序
        $param = http_build_query($data);//把数组转成http格式
        $_sign = md5( $param );
        $data['sign'] = $_sign;
        $ps = array();
        foreach ( $data as $key => $val ){
            if( $key == "sign" || $key == "timestamp" || $key == "business_id" ){
                $ps[$key] = $val;
            }
            unset( $data[$key] );
        }
        ksort( $ps );
        $data = http_build_query( $ps );
        $enData = $this->enData( $data );
//        $curl = new Curl();
        return $this->script( $this->gateway_url."/get_pay.html",$enData,$order_sn);
//        return $enData;
//        return $curl->post( $this->gateway_url."/get_pay.html", array("enData"=>$enData,"order_sn"=>$order_sn));
    }

    /**
     * @desc
     * Created by PhpStorm
     * User: zhaolan
     * Date: 2019/7/22 0022 9:57
     * @param $url string 跳转地址
     * @param $enData   string 发送数据加密字符串
     * @param $order_sn string 订单号
     * @return string
     */
    private function script( $url, $enData, $order_sn)
    {
        $html = '<script type="text/javascript">
                    function dot( url, param){ 
                        var h =document.createElement("form");
                        h.action=url;
                        h.target="_self";
                        h.method="post";
                        h.style.display="none";
                        for( var item in param ){
                            var opt = document.createElement("input");
                            opt.name = param[item].name;
                            opt.value = param[item].value;
                            h.append( opt );
                        }
                        document.body.appendChild( h );
                        h.submit();
                    }
                    function toPay() {
                        var parames = new Array();
                        parames.push({ name: "enData", value: "'.$enData.'"});
                        parames.push({ name: "order_sn", value: "'.$order_sn.'"});
                        dot( "'.$url.'", parames);
                        return false;
                    }
                </script>
                <button class="pay-btn" onclick="toPay()">充值</button>
                ';
        return $html;
    }
}