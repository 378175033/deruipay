<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:79:"D:\phpStudy\PHPTutorial\WWW\F4\public/../application/manage\view\banks\add.html";i:1562745888;s:71:"D:\phpStudy\PHPTutorial\WWW\F4\application\manage\view\common\head.html";i:1561360432;}*/ ?>
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
                <label class="layui-form-label">银行名称</label>
                <div class="layui-input-block">
                    <input class="layui-input" name="name" placeholder="银行名称" lay-verify="name">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">银行图片</label>
                <div class="layui-upload-drag" id="test10">
                    <input id="input_img" type="text" style="display: none" lay-verify="picture" name="picture">
                    <i class="layui-icon"></i>
                    <p>点击上传，或将图片拖拽到此处</p>
                </div>

                <div class="layui-input-block img" style="display: none;margin-top: 10px">
                    <img id="img" width="150" height="80" src="" alt="">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">银行类别</label>
                <div class="layui-input-block">
                    <input class="layui-input" name="rate" placeholder="银行类别">
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
                    return "银行名称不能为空"
                }
            }
        });
        form.on('submit(formDemo)', function(data){

            console.log(data);
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
    layui.use('upload', function(){
        var upload = layui.upload;
        //执行实例
        var uploadInst = upload.render({
            elem: '#test10', //绑定元素
            url: '<?php echo url("Upload/upload"); ?>', //上传接口
            accept: 'images', //允许上传的文件类型
            data : {"folder": "passageway"},//上传接口额外参数
            size : 2048,//设置文件最大可允许上传的大小，单位 KB
            multiple: false,//是否允许多文件上传。
            acceptMime: 'image/*',//规定打开文件选择框时，筛选出的文件类型
            //,size: 50 //最大允许上传的文件大小

            done: function(res){
                //上传完毕回调
                $('.img').css('display','block');
                $('#img').attr('src','/uploads/passageway/'+res.msg);
                $('#input_img').val('/uploads/passageway/'+res.msg);
            }
            ,error: function(){
                //请求异常回调
            }
        });
    });
</script>