<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:71:"D:\a_project\F4\public/../application/manage\view\business\account.html";i:1561012380;s:56:"D:\a_project\F4\application\manage\view\common\head.html";i:1560933288;}*/ ?>
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
                <label class="layui-form-label">当前余额</label>
                <div class="layui-input-block">
                    <p style="line-height: 36px">&yen;<?php echo $data['money']; ?></p>
                </div>
            </div>
            <?php if($type == '1'): ?>
                <div class="layui-form-item">
                    <label class="layui-form-label">改变方式</label>
                    <div class="layui-input-block">
                        <input type="radio" name="way" value="1" title="增加" checked>
                        <input type="radio" name="way" value="2" title="减少">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">改变数量</label>
                    <div class="layui-input-block">
                        <input type="number" class="layui-input" name="inc" placeholder="请设置增加/减少金额">
                    </div>
                </div>
            <?php endif; if($type == '2'): ?>
            <div class="layui-form-item">
                <label class="layui-form-label">冻结余额</label>
                <div class="layui-input-block">
                    <input type="number" class="layui-input" name="frozen_money" value="<?php echo $data['frozen_money']; ?>" placeholder="请设置冻结余额">
                </div>
            </div>
            <?php endif; ?>
            <div class="layui-form-item">
                <label class="layui-form-label">变动原因</label>
                <div class="layui-input-block">
                    <textarea name="desc" class="layui-textarea" placeholder="请输入您的变更原因"></textarea>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
                    <input type="hidden" name="money" value="<?php echo $data['money']; ?>">
                    <input type="hidden" name="type" value="<?php echo $type; ?>">
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
        var flag = true;
        form.on('submit(formDemo)', function(data){
            if( flag ){
                $.post('<?php echo url("account"); ?>',data.field,function (res) {
                    if( res.code === 1 ){
                        flag = false;
                        toastr.success( res.msg ,function () {
                            setTimeout(function () {
                                parent.location.reload();
                                var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                                parent.layer.close(index);
                            },1000)
                        });
                    } else {
                        toastr.error( res.msg )
                    }
                })
            }
            return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
        });
    });
</script>