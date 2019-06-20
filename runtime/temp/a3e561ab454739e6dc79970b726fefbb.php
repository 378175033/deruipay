<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:67:"D:\a_project\F4\public/../application/manage\view\pay_modl\add.html";i:1560933288;s:56:"D:\a_project\F4\application\manage\view\common\head.html";i:1560933288;}*/ ?>
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
        .mt20{
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="layui-card layui-col-md10 layui-col-md-offset1">
    <div class="layui-card-body ">
        <form class="layui-form">

            <div class="layui-form-item">
                <label class="layui-form-label">通道名称</label>
                <div class="layui-input-block">
                    <input class="layui-input" name="name" placeholder="通道名称" lay-verify="name">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">通道编号</label>
                <div class="layui-input-block">
                    <input class="layui-input" name="pay_type" placeholder="通道编号">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">基础费率</label>
                <div class="layui-input-block">
                    <input class="layui-input" name="rate" placeholder="基础费率">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">最小打款金额</label>
                <div class="layui-input-block">
                    <input class="layui-input" name="mini" value="0.01" placeholder="最小打款金额">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">最大打款金额</label>
                <div class="layui-input-block">
                    <input class="layui-input" name="max" value="49999" placeholder="最大打款金额">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">是否可用</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="status" value="1" checked lay-skin="switch" lay-text="是否可用">
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn submitBtn_add" lay-submit lay-filter="formDemo">立即提交</button>
                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript" src="/static/manage/js/jquery.js"></script>
<script type="text/javascript" src="/static/layui/layui.js"></script>
<script type="text/javascript" src="/static/manage/js/toastr/toastr.js"></script>
<script type="text/javascript" src="/static/manage/js/base.js"></script>
<script>
    layui.use(['form'], function() {
        var form = layui.form
        //自定义验证规则
        form.verify({
            name: function(value) {
                if( value ===  "" ){
                    return "用户名不能为空"
                }
            }
        });
        form.verify({
            pay_type: function(value) {
                if( value ===  "" ){
                    return "通道编码不能为空"
                }
            }
        });
        form.on('submit(formDemo)', function(data){

            console.log(data.field);
            $.post('<?php echo url("add"); ?>',data.field,function (res) {
                console.log( res )
                if( res.code === 1 ){
                    toastr.success( res.msg ,function () {
                        setTimeout( function () {
                            parent.location.reload();
                            var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                            parent.layer.close(index);
                        },1000)
                    });
                } else {
                    toastr.error( res.msg )
                }
            })
            return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
        });
    });
</script>