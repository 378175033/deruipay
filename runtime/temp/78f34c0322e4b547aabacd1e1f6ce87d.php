<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:85:"D:\phpStudy\PHPTutorial\WWW\F4\public/../application/business\view\index\welcome.html";i:1560925714;s:73:"D:\phpStudy\PHPTutorial\WWW\F4\application\business\view\common\head.html";i:1561025183;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>后台管理</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" href="/static/layui/css/layui.css" media="all"/>
    <link rel="stylesheet" href="/static/manage/js/toastr/build/toastr.css">
    <style>
        .align-right{
            text-align: right;
        }
        .mt20{
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="layui-container">
    <div class="layui-row">
        <div class="layui-col-md9">
            <div class="fl">
                <p class="">
                    <span>账户余额：</span>
                </p>
                <p>&yen;<?php echo $user['money']; ?></p>
            </div>
        </div>
        <div class="layui-col-md3">
        </div>
    </div>
</div>