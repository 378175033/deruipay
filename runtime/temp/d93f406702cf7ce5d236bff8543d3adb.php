<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:64:"D:\a_project\F4\public/../application/manage\view\user\edit.html";i:1560835034;s:56:"D:\a_project\F4\application\manage\view\common\head.html";i:1560933288;}*/ ?>
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
                <label class="layui-form-label">用户名/登录名</label>
                <div class="layui-input-block">
                    <input class="layui-input" name="username" placeholder="写入用户名/登录名" lay-verify="name" value="<?php echo $data['username']; ?>">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">用户昵称</label>
                <div class="layui-input-block">
                    <input class="layui-input" name="nickname" placeholder="请输入用户昵称" value="<?php echo $data['nickname']; ?>">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">密码</label>
                <div class="layui-input-block">
                    <input class="layui-input" type="password" name="password" placeholder="请输入密码">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">手机号码</label>
                <div class="layui-input-block">
                    <input class="layui-input" name="mobile" lay-verify="required|phone|number" placeholder="请输入手机号码" value="<?php echo $data['mobile']; ?>">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">设置排序</label>
                <div class="layui-input-block">
                    <input class="layui-input" name="sort" value="<?php echo $data['sort']; ?>" placeholder="设置排序">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">是否隐藏</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="status" value="1" <?php if(!(empty($data['status']) || (($data['status'] instanceof \think\Collection || $data['status'] instanceof \think\Paginator ) && $data['status']->isEmpty()))): ?>checked<?php endif; ?> lay-skin="switch" lay-text="显示|隐藏">
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
                    <button class="layui-btn" lay-submit lay-filter="formDemo">立即提交</button>
                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript" src="/static/manage/js/jquery.js"></script>
<script type="text/javascript" src="/static/layui/layui.js"></script>
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
            $.post('<?php echo url("edit"); ?>',data.field,function (res) {
                console.log( res );
                if( res.code === 1 ){
                    layer.msg( res.msg ,{icon: 1});
                    parent.location.reload();
                    var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                    parent.layer.close(index);

                } else {
                    layer.msg( res.msg ,{icon: 2})
                }
            })
            return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
        });
    });
</script>