<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/7/19 0019
 * Time: 11:33
 */
//echo '<pre>';
$isPost = $_SERVER['REQUEST_METHOD'] == "POST" ? true : false ;
$config = require "config/config.php";
require_once "service/TradeService.php";
$service = new TradeService( $config );
$order = [
    "order_sn"  => "sn_".time(), //订单号
];
$res = $service->jumpPay( $order );
?>
<!doctype html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ceshi</title>
</head>
<body>
<?php echo $res;?>
</body>
</html>
