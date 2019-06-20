<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:66:"D:\a_project\F4\public/../application/manage\view\user\center.html";i:1560933288;s:56:"D:\a_project\F4\application\manage\view\common\head.html";i:1560933288;}*/ ?>
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
<div class="layui-card layui-col-md10 layui-col-md-offset1 mt20">
    <div class="layui-card-header">基本信息</div>
    <div class="layui-card-body ">
        <form class="layui-form">
            <div class="left-box left">
                <?php if(empty($user['avatar']) || (($user['avatar'] instanceof \think\Collection || $user['avatar'] instanceof \think\Paginator ) && $user['avatar']->isEmpty())): ?>
                    <img src="http://m.zhengjinfan.cn/images/0.jpg" alt="" class="layui-circle avatar" width="160px">
                <?php else: ?>
                    <img src="/uploads/head/<?php echo $user['avatar']; ?>" alt="" class="layui-circle avatar" width="160px">
                <?php endif; ?>
                <button type="button" class="layui-btn" id="upload">
                    <i class="layui-icon">&#xe67c;</i>更换头像
                </button>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript" src="/static/manage/js/jquery.js"></script>
<script type="text/javascript" src="/static/layui/layui.js"></script>
<script type="text/javascript" src="/static/manage/js/toastr/toastr.js"></script>
<script type="text/javascript" src="/static/manage/js/base.js"></script>
<script>
    layui.use(['form','upload'], function() {
        var form = layui.form,upload=layui.upload;
        var uploadInst = upload.render({
            elem: '#upload' //绑定元素
            ,url: '<?php echo url("Upload/upload"); ?>' //上传接口
            ,accept: 'images' //允许上传的文件类型
            ,data : {"folder": "head"}//上传接口额外参数
            ,size : 2048//设置文件最大可允许上传的大小，单位 KB
            ,multiple: false//是否允许多文件上传。
            ,acceptMime: 'image/*'//规定打开文件选择框时，筛选出的文件类型
            //,size: 50 //最大允许上传的文件大小
            ,done: function(res){
                if( res.code === 1 ){
                    $.post( "<?php echo url('Api/updateUser'); ?>",{avatar: res.msg},function ( data ) {
                        if( data.code === 1 ){
                            toastr.success( "头像更换成功！",function () {
                                $('.avatar',window.parent.document).attr( 'src', "/uploads/head/"+res.msg );
                                $('.avatar').attr( 'src', "/uploads/head/"+res.msg );
                            })
                        } else {
                            toastr.error( res.msg )
                        }
                    })
                } else {
                    toastr.error( res.msg )
                }


                //上传完毕回调
            }
            ,error: function(){
                //请求异常回调
            }
        });
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