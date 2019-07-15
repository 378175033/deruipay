<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:78:"D:\phpStudy\PHPTutorial\WWW\F4\public/../application/manage\view\menu\add.html";i:1560827637;s:71:"D:\phpStudy\PHPTutorial\WWW\F4\application\manage\view\common\head.html";i:1561360432;}*/ ?>
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
        .container{
            width: 98%;
            margin: 0 auto;
        }
    </style>
</head>
<body>
<div class="layui-card layui-col-md10 layui-col-md-offset1">
    <div class="layui-card-body ">
        <form class="layui-form">
            <div class="layui-form-item">
                <label class="layui-form-label">选择上级菜单</label>
                <div class="layui-input-block">
                    <select name="parent_id" class="select-content">
                        <option value="0">-设为顶级菜单-</option>
                        <?php echo controller("Api")->getMenu( request()->param('pid',0,'intval') ); ?>
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">菜单名称</label>
                <div class="layui-input-block">
                    <input class="layui-input" name="name" placeholder="写入菜单名称" lay-verify="name">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">控制器名称</label>
                <div class="layui-input-block">
                    <input class="layui-input" name="controller" placeholder="请输入控制器名称">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">方法名称</label>
                <div class="layui-input-block">
                    <input class="layui-input" name="action" placeholder="请输入方法名称">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">图标选择</label>
                <div class="layui-input-block">
                    <input class="layui-input" name="icon" placeholder="请选择菜单图标">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">设置排序</label>
                <div class="layui-input-block">
                    <input class="layui-input" name="sort" value="10000" placeholder="设置排序">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">是否隐藏</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="status" value="1" checked lay-skin="switch" lay-text="显示|隐藏">
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
        form.on('submit(formDemo)', function(data){
            console.log(data.field)
            $.post('<?php echo url("add"); ?>',data.field,function (res) {
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
    });
</script>