<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/18 0018
 * Time: 14:05
 *  支付方式
 *
 */
namespace app\manage\controller;
use app\common\controller\Manage;
//use app\common\controller\AopClient;
//use app\common\controller\AlipayFundTransToaccountTransferRequest;
//use app\common\f2fpay\model\builder\AlipayTradePrecreateContentBuilder;
//use app\common\f2fpay\model\builder\ExtendParams;
//use app\common\f2fpay\model\builder\GoodsDetail;
//
//use app\common\f2fpay\service\AlipayTradeService;

class PayModl extends Manage
{
//    static protected $payBabay;
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        $this->table = "passageway";
        $this->model = model("passageway");
//        $this->pay_babay_config();
    }
//    /**
//    支付宝支付配置
//     */
//    public function pay_babay_config()
//    {
//        $aop = new AopClient ();
//        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
//        $aop->appId = '2019061965613516';
//        $aop->rsaPrivateKey = "MIIEpAIBAAKCAQEAwg8LiXU5Cj0EFgaFEpbOo7cpRGNVbOxRLo4ZjfC/bgn1Aehe9lvSntxjwUcsujzn2m/EPnp6iFAfgGeXUE8/rLFumpPwtUxfyPUBVsoAAZ1zubMxkrySUzq9yKSPfhmug/cIzHwcjsv19QsmEm+o2so63uuNcsOe/eGQuSSy+RaiR7Gy8jIBhwYB/dt3KQL5gKm3V9W8OOYpK9dw5feNGinSpbET2T+14UWcVq2Lr7WQ5YFNs+ZekO1v+866o+WiG21IHcbFQJDXTW+DNVSXfsI9RxapB+VSgBApEXUaCCa+W5SEEbQNLfnxCMRLkBb4xBVnHCianjINZO0x2LWIcwIDAQABAoIBACrCt+8VFnmMEl9sFlyPQH9Qt9Yq8ULsG8NfaoAdYYE0znkaI/qzJwj8VTrcnR14mDpI0HxX7rIkvZxEt1Hp9ITwIAgNu0enyZ91ZVMjdbblY/+yXaUQyklusy0IHdpSfGL1x0mPu5c3mD3jtALx+cokL665RtTYCCu3TXWOgaVjFiog8V3E0cEl1ekPxGH9+BuWqeIhq61KmbluJWtsvKauCj8XbEXfJ7Q1SLlbUOjJYT9bWWsfUPE7TBFbUSuC9KK8NQYebXwwBExNAMIU5mbwYKIcWKAD2skNw0RVk2g5bUPQcMd4Vl++BS9DMvTCt9wL8gNWT3RSVgnaJpsQBXECgYEA4Jou0pkHm/RvuOT8Si5PtMSKf4i9HlLzd3TEpI6/mHBDwydDptbYNAwHfH2zQMkynw2JDqg0NL9VMHATJUmrPm6wB+lzajXUw4VO0g48xC8lTzi1nYGnr6Zg//0T3Gjt4cvAoeO/Q05w5Su8quEgVEBsSaKn8zdjIqqCL4iXQ2sCgYEA3S/OFaRF3WRci/LaOM7ECWjOw1yoFlaVGABKHCyig2D5WWEBh+6T3k9UsUVhJEVUaPC5Y6Tq8Vj5UVCR5a2LT9fodN3O8I+S/peMr4m73YZXUoafriX0jMyXfSQJLRF1tfKOZlIHedU/G2e9OOiVPOJ4PWoJ4nm6HcwWua4vmRkCgYAbwLF8cFBSYvfTHuhVujc7HPYIIDtOHe3bmuAZfVILYgPdf2KKoQ2CEOJz7YxSuwm4QZHn77zTr7i1DYQwHVQ9mKvDroMGYrRxnG1K41t62mB/04ANgFHaEHL37quflo+eUPDykBO4G18z0h2z97Fo97TpvGGIWhWz2OHRQc1/FQKBgQCejOALP2AdXQ3B++lVg1Ge9RQRkl+i85mYRMza+VvdFSxoV1MDn487cl5hXDxQBaqGNtiNhvAq5P6CvWB35TjRmRE2hLEMW76g5P2h7vdNyjjaHUplSSvNqfKFb8lsFvHr5N0Sl4ZoXOYJvQk0u/QOWsCaNWK0h1FUfrFjlGrmMQKBgQC0nIaylG7kqwOvcj2oNR5EjfELQgx7BGGhYUoKXy4WgWi+kDWV4vuIeImt9xJ3xGx5aX77tnp8iUIcVEHYzAieH3O41uBkBpXCPb71+XHBhBFnXULfPslO8o4OROWBXEQPlevaQjTb3nxGmXhTOcBEt5zdH2xa+p8jqGegHXXuAg==";
//        $aop->alipayrsaPublicKey='MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAl6G/RR5YKwdr22i88XNVsydgx2JF+ekqfBI+viHBnS5ClLct8PyM54ETCytOQgybz4HyM8zNzaeQIrRiyl2Xn0oK7aNyVbL7rvsI4dbk8WPZjZIoNF4OyERJO/yP6UQuGGwdiYtYDZ20pE38OfMaeusCb4FhXtuzbrTyx1FngU0goXozYaarBh4iz2fNSn3bUKkg3Gj5uKb2Hy54WOJf+T04elsJ5moX8cKYNyl+X/kLEIHXKyn1WPT4/aSCPwKD6q1c/G+BZ8xrsg5NOFEFoYg1+H7XkNol3uhwrEjd+3EQg1i8seONcp7o1ObY09nxHX2gZOn6fX/pp5YIEizhUwIDAQAB';
//        $aop->apiVersion = '1.0';
//        $aop->signType = 'RSA2';
//        $aop->postCharset='UTF-8';
//        $aop->format='json';
//        return self::$payBabay = $aop;
//    }

    /**
     *  支付
     * */
    public function pay_babay()
    {


    }

    public function get4select()
    {
        $passageway_list = $this->model->where('delete_time', '=', 0)->column('name', 'id');
        return $passageway_list;
    }


    /**
     *  商家收款 （当面付）
     *
     */
    public function Face( $data )
    {
        require_once dirname(dirname(dirname(dirname(__FILE__)))) . "/vendor/dangmianfu_demo_php/f2fpay/model/builder/AlipayTradePrecreateContentBuilder.php";
        require_once dirname(dirname(dirname(dirname(__FILE__)))) . "/vendor/dangmianfu_demo_php/f2fpay/service/AlipayTradeService.php";
        require_once dirname(dirname(dirname(dirname(__FILE__)))) . "/vendor/dangmianfu_demo_php/f2fpay/qrpay_test.php";
        $orderTitel = $data['title'];
        $goods = $data['money'];
        $outTradeNo = "zcss" . date('Ymdhis') . mt_rand(100, 1000);
        $succ = pay_face($outTradeNo, $orderTitel, $goods, $config);
        echo $succ;
        die;
        if ($succ != 1 && $succ != 3) {
            $this->success('支付宝创建订单二维码成功', '', $succ);
        } elseif ($succ == 1) {
            $this->error('支付宝创建订单二维码失败', '');
        } else {
            $this->error('系统异常，状态未知!!', '');
        }
    }
    /**
     * 支付宝回调
     * */
    public function succNotifyServer()
    {
        if (!isset($_GET['ACCOUNT_NO'])) return 'error';
        $ACCOUNT_NO = $_GET['ACCOUNT_NO'];
        $MOBILE = $_GET['MOBILE'];
        $AMOUNT = $_GET['AMOUNT'];
        $BATCHID = $_GET['BATCHID'];
        $SETTDAY = $_GET['SETTDAY'];
        $FINTIME = $_GET['FINTIME'];
        $SUBMITTIME = $_GET['SUBMITTIME'];
        $SN = $_GET['SN'];
        $POUNDAGE = $_GET['POUNDAGE'];
        $USERCODE = $_GET['USERCODE'];
        $SIGN = $_GET['SIGN'];//签名后的字符串

    }

    /**
     *支付宝转账接口  必须要企业号才行
     *
     */
//    public function Transfer()
//    {

//}

}