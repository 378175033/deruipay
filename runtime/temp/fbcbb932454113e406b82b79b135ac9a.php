<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:84:"D:\phpStudy\PHPTutorial\WWW\F4\public/../application/business\view\user\profile.html";i:1562821777;s:73:"D:\phpStudy\PHPTutorial\WWW\F4\application\business\view\common\head.html";i:1561025183;}*/ ?>
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
                <tr><td class="align-right">登录名</td><td><?php echo $user['login_name']; ?></td></tr>
                <tr><td class="align-right">手机号</td><td><?php echo $user['mobile']; ?></td></tr>
                <tr><td class="align-right">入驻时间</td><td><?php echo $user['create_time']; ?></td></tr>
                <tr><td class="align-right">可用余额</td><td style="color: green;"><?php echo $user['money']; ?></td></tr>
                <tr><td class="align-right">冻结余额</td><td style="color: #f00;"><?php echo $user['frozen_money']; ?></td></tr>
                <tr><td class="align-right">最近登录时间</td><td><?php echo $user['last_login_time']; ?></td></tr>
                <tr><td class="align-right">最近登录IP</td><td><?php echo $user['last_login_ip']; ?></td></tr>
                <tr><td class="align-right">最近登录地址</td><td><?php echo GetIpLookup($user['last_login_ip']); ?></td></tr>
                <tr>
                    <td colspan="2">
                        <a href="javascript:;" class="layui-btn layui-btn changePass">修改密码</a>
                        <a href="javascript:;" class="layui-btn layui-btn-normal safePass">设置支付安全密码</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<script type="text/html" id="safe">
    <form class="layui-form mt20 layui-col-md10">
        <div class="layui-form-item">
            <label class="layui-form-label">获取验证码</label>
            <div class="layui-input-block">
                <button class="send_sms layui-btn layui-btn-primary" type="button">点击获取验证码</button>
                <span style="margin-left: 10px;">查看<?php echo $user['mobile']; ?>短信</span>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">验证码</label>
            <div class="layui-input-block">
                <input class="layui-input" name="verify" placeholder="获取到的手机验证码">
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn submitBtn_add" lay-submit lay-filter="demo">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
    </form>
</script>
<script type="text/javascript" src="/static/manage/js/jquery.js"></script>
<script type="text/javascript" src="/static/layui/layui.js"></script>
<script type="text/javascript" src="/static/manage/js/toastr/toastr.js"></script>
<script type="text/javascript" src="/static/manage/js/base.js"></script>
<script>
    $('.changePass').click(function () {
        layui.use('layer',function () {
            layer = layui.layer;
            layer.ready(function () {
                var cs = layer.load(2);
                layer.prompt({
                    formType: 1,
                    title: '请输入原密码',
                    shade: 0
                }, function(value, index, elem){
                    //判断原密码是否一致
                    $.post('',{act:'ckPass',value:value},function (res) {
                        if( res.code === 1 ){
                            layer.close(index);
                            layer.prompt({
                                formType:1,
                                title:'重新设置密码',
                                shade: 0
                            },function ( value, index) {
                                $.post("",{act:'rePass',value:value},function (res) {
                                    if( res.code === 1 ){
                                        toastr.success( res.msg ,function () {
                                            setTimeout(function () {
                                                layer.close( index );
                                            },1000)
                                        });
                                    } else {
                                        toastr.error( res.msg );
                                    }
                                })
                            })
                        } else {
                            toastr.error( res.msg );
                        }
                    })

                });
                layer.close( cs );
            })

        })
    })
    $('.safePass').click(function () {
        layui.use(['layer'],function () {
            var layer = layui.layer;
            layer.open({
                type : 1,
                title: "设置安全密码",
                area: ['600px','300px'],
                content:$('#safe').html()
            })
        })
    })
    var m = 60;
    function smsTimeout()
    {
        var index = setInterval(function () {
            m--;
            if( m < 0 ){
                clearInterval( index );
                m = 60;
                $('.send_sms').removeClass("layui-btn-disabled").addClass("layui-btn-primary");
                $('.send_sms').html( "点击重新获取验证码" );
            } else{
                $('.send_sms').removeClass("layui-btn-primary").addClass("layui-btn-disabled");
                $('.send_sms').html( m+"秒后可重新获取");
            }
        },50)
    }
    $(document).ready(function () {
        $(document).on( "click", ".send_sms",function () {
            if( $(this).hasClass("layui-btn-primary") ){
                smsTimeout();
            }
        })
    })

    layui.use('form',function () {
        form = layui.form;
        form.on("submit(demo)",function () {
            return false;
        })
    })
</script>