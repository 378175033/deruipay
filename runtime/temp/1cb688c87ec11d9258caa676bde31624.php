<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:84:"D:\phpStudy\PHPTutorial\WWW\F4\public/../application/business\view\user\account.html";i:1561698917;s:73:"D:\phpStudy\PHPTutorial\WWW\F4\application\business\view\common\head.html";i:1561025183;}*/ ?>
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
<div class="layui-col-md-offset1 layui-col-xs10 mt20">
    <div class="layui-collapse" lay-filter="demo">
        <div class="layui-colla-item">
            <h2 class="layui-colla-title">支付宝</h2>
            <div class="layui-colla-content layui-show layui-form">
                <div class="layui-form-item">
                    <label class="layui-form-label">账号</label>
                    <div class="layui-input-block">
                        <input class="layui-input" name="ali_name" value="<?php echo (isset($data['ali_name']) && ($data['ali_name'] !== '')?$data['ali_name']:''); ?>" placeholder="请输入账号">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">到账用户</label>
                    <div class="layui-input-block">
                        <input class="layui-input" name="ali_user" placeholder="请输入用户名" value="<?php echo (isset($data['ali_user']) && ($data['ali_user'] !== '')?$data['ali_user']:''); ?>">
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn submitBtn_add" lay-submit lay-filter="demo1">立即提交</button>
                        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="layui-colla-item">
            <h2 class="layui-colla-title">微信</h2>
            <div class="layui-colla-content layui-show layui-form">
                <div class="layui-form-item">
                    <label class="layui-form-label">账号</label>
                    <div class="layui-input-block">
                        <input class="layui-input" name="we_name" value="<?php echo (isset($data['we_name']) && ($data['we_name'] !== '')?$data['we_name']:''); ?>" placeholder="请输入账号">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">到账用户</label>
                    <div class="layui-input-block">
                        <input class="layui-input" name="we_user" placeholder="请输入用户名" value="<?php echo (isset($data['we_user']) && ($data['we_user'] !== '')?$data['we_user']:''); ?>">
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn submitBtn_add" lay-submit lay-filter="demo2">立即提交</button>
                        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="layui-colla-item">
            <h2 class="layui-colla-title">银行卡</h2>
            <div class="layui-colla-content layui-show layui-form">
                <div class="layui-form-item">
                    <label class="layui-form-label">账号</label>
                    <div class="layui-input-block">
                        <input class="layui-input" name="un_name" placeholder="请输入账号" value="<?php echo (isset($data['un_name']) && ($data['un_name'] !== '')?$data['un_name']:''); ?>">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">银行</label>
                    <div class="layui-input-block">
                        <select name="un_bank">
                            <option value="0">请选择银行</option>
                            <?php 
                            $bank = [ '中国银行','招商银行','民生银行','建设银行','农业银行','中国邮政储蓄银行','工商银行','交通银行','华夏银行',
                            '中信银行','平安银行','浦发银行','光大银行','兴业银行','北京银行','广发银行'];
                             if(is_array($bank) || $bank instanceof \think\Collection || $bank instanceof \think\Paginator): $i = 0; $__LIST__ = $bank;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$bk): $mod = ($i % 2 );++$i;?>
                                <option value="<?php echo $bk; ?>" <?php if($data['un_bank'] == $bk): ?>selected<?php endif; ?>><?php echo $bk; ?></option>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">开户支行</label>
                    <div class="layui-input-block">
                        <input class="layui-input" name="un_branch" placeholder="请输入开户支行" value="<?php echo (isset($data['un_branch']) && ($data['un_branch'] !== '')?$data['un_branch']:''); ?>">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">到账用户</label>
                    <div class="layui-input-block">
                        <input class="layui-input" name="un_user" placeholder="请输入用户名" value="<?php echo (isset($data['un_user']) && ($data['un_user'] !== '')?$data['un_user']:''); ?>">
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn submitBtn_add" lay-submit lay-filter="demo3">立即提交</button>
                        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="/static/manage/js/jquery.js"></script>
<script type="text/javascript" src="/static/layui/layui.js"></script>
<script type="text/javascript" src="/static/manage/js/toastr/toastr.js"></script>
<script type="text/javascript" src="/static/manage/js/base.js"></script>
<script>
    //注意：折叠面板 依赖 element 模块，否则无法进行功能性操作
    layui.use(['element','form'], function(){
        var element = layui.element,
            form=layui.form;
        for( var i= 1; i < 4; i++){
            form.on('submit(demo'+i+')', function(data){
                $.post('',data.field,function (res) {
                    if( res.code === 1 ){
                        toastr.success( res.msg ,function () {
                            parent.location.reload();
                            var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                            parent.layer.close(index);
                        });
                    } else {
                        toastr.error( res.msg )
                    }
                })
                return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
            });
        }

    });
</script>
