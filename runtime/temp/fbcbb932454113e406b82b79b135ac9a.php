<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:84:"D:\phpStudy\PHPTutorial\WWW\F4\public/../application/business\view\user\profile.html";i:1561025183;s:73:"D:\phpStudy\PHPTutorial\WWW\F4\application\business\view\common\head.html";i:1561025183;}*/ ?>
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
<div class="layui-card mt20">
    <div class="layui-card-header">账户信息</div>
    <div class="layui-card-body">
        <table class="layui-table">
            <colgroup>
                <col width="150">
                <col width="250">
            </colgroup>
            <tbody>
                <tr><td class="align-right">商户号</td><td><?php echo $user['shop_sn']; ?></td></tr>
                <tr><td class="align-right">商户名</td><td><?php echo $user['name']; ?></td></tr>
                <tr><td class="align-right">登录名</td><td><?php echo $user['name']; ?></td></tr>
                <tr><td class="align-right">手机号</td><td><?php echo $user['mobile']; ?></td></tr>
                <tr><td class="align-right">入驻时间</td><td><?php echo $user['create_time']; ?></td></tr>
            </tbody>
        </table>
    </div>
</div>
