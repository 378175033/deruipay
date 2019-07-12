<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:81:"D:\phpStudy\PHPTutorial\WWW\F4\public/../application/manage\view\notice\edit.html";i:1561025183;s:71:"D:\phpStudy\PHPTutorial\WWW\F4\application\manage\view\common\head.html";i:1561360432;}*/ ?>
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
                <label class="layui-form-label">公告标题</label>
                <div class="layui-input-block">
                    <input class="layui-input name" name="name" placeholder="公告标题" lay-verify="name" value="<?php echo $data['name']; ?>">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">公告简介</label>
                <div class="layui-input-block">
                    <textarea name="desc" id="" class="layui-textarea" placeholder="输入公告简介"><?php echo $data['desc']; ?></textarea>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">展示对象</label>
                <div class="layui-input-block">
                    <input type="radio" name="to_user" value="0" title="全部" <?php if($data['to_user'] == '0'): ?>checked<?php endif; ?>>
                    <input type="radio" name="to_user" value="1" title="管理员" <?php if($data['to_user'] == '1'): ?>checked<?php endif; ?>>
                    <input type="radio" name="to_user" value="2" title="商户" <?php if($data['to_user'] == '2'): ?>checked<?php endif; ?>>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">公告详情</label>
                <div class="layui-input-block">
                    <!-- 加载编辑器的容器 -->
                    <script id="container" name="content" type="text/plain"><?php echo $data['content']; ?></script>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">公告排序</label>
                <div class="layui-input-block">
                    <input class="layui-input" name="sort" placeholder="公告排序" type="number" value="<?php echo $data['sort']; ?>">
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
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
<!-- 配置文件 -->
<script type="text/javascript" src="/static/ueditor/ueditor.config.js"></script>
<!-- 编辑器源码文件 -->
<script type="text/javascript" src="/static/ueditor/ueditor.all.js"></script>
<script>
    <!-- 实例化编辑器 -->
    var ue = UE.getEditor('container');
    layui.use(['form'], function() {
        var form = layui.form
        //自定义验证规则
        form.verify({
            name: function(value) {
                if( value ===  "" ){
                    return "定义的公告标题不能为空"
                }
            }
        });
        form.on('submit(formDemo)', function(data){
            $.post('<?php echo url("edit"); ?>',data.field,function (res) {
                if( res.code === 1 ){
                    toastr.success( res.msg , function () {
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