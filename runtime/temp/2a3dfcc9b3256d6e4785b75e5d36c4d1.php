<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:58:"E:\www\F4\public/../application/manage\view\user\auth.html";i:1560835295;s:50:"E:\www\F4\application\manage\view\common\head.html";i:1560910332;}*/ ?>
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
<link rel="stylesheet" href="/static/tree/css/zTreeStyle/zTreeStyle.css" type="text/css">
<link rel="stylesheet" href="/static/tree/css/demo.css" type="text/css">
<div class="layui-card layui-col-md10 layui-col-md-offset1">
    <div class="layui-card-body ">
        <form class="layui-form">
            <div class="layui-form-item">
                <div class="zTreeDemoBackground left" style="margin: 0 auto;">
                    <ul id="tree" class="ztree"></ul>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <input type="hidden" name="rule" id="rule" value="<?php echo $rule; ?>">
                    <button class="layui-btn submitBtn_add" lay-submit lay-filter="formDemo">立即提交</button>
                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript" src="/static/manage/js/jquery.js"></script>
<script type="text/javascript" src="/static/layui/layui.js"></script>
<script type="text/javascript" src="/static/tree/js/jquery.ztree.core.js"></script>
<script type="text/javascript" src="/static/tree/js/jquery.ztree.excheck.js"></script>
<script>
    var setting = {
        check: {
            enable: true
        },
        data: {
            simpleData: {
                enable: true
            }
        }
    };
    var zTree = "";
    $(document).ready(function(){
        var zNodes = "";
        $.post( '<?php echo url("Api/getAuth"); ?>',{id:'<?php echo $id; ?>'},function (res) {
            zNodes = res.msg;
            console.log( res )
            $.fn.zTree.init($("#tree"), setting, zNodes);
            zTree = $.fn.zTree.getZTreeObj("tree");
            zTree.setting.check.chkboxType = { "Y":'ps', "N":'ps'};
        })
    });
    $(document).on('click','.chk',function () {
        var nodes = zTree.getCheckedNodes();
        var rule = new Array();
        for( var i = 0; i < nodes.length;i++)
        {
            rule.push( nodes[i]['id'] );
        }
        $('#rule').val( rule.join(',') );
    })
    layui.use(['form'], function() {
        var form = layui.form;
        form.on('submit(formDemo)', function(data){
            console.log(data.field)
            $.post('<?php echo url("auth"); ?>',data.field,function (res) {
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