<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:83:"D:\phpStudy\PHPTutorial\WWW\F4\public/../application/manage\view\setting\index.html";i:1562135175;s:71:"D:\phpStudy\PHPTutorial\WWW\F4\application\manage\view\common\head.html";i:1561360432;}*/ ?>
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
<style>
    .layui-form-label{
        width: 20%;
    }
    .layui-input-block{
        margin-left: 29%;
    }
</style>
<div class="layui-card layui-col-md10 layui-col-md-offset1 mt20">
    <div class="layui-card-header">
        基本信息
    </div>
    <div class="layui-card-body ">
        <form class="layui-form">
            <div class="layui-col-md6">
                <div class="layui-form-item">
                    <label class="layui-form-label">订单有效期</label>
                    <div class="layui-input-block">
                        <input type="number" id="close" value="<?php echo (isset($data['close']) && ($data['close'] !== '')?$data['close']:''); ?>" lay-verify="required" name="close" placeholder="请输入创建的订单几分钟后失效" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">异步回调地址</label>
                    <div class="layui-input-block">
                        <input class="layui-input" name="notifyUrl" placeholder="请输入异步回调地址" value="<?php echo (isset($data['notifyUrl']) && ($data['notifyUrl'] !== '')?$data['notifyUrl']:''); ?>">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">同步回调地址</label>
                    <div class="layui-input-block">
                        <input class="layui-input" name="returnUrl" placeholder="请输入同步回调地址" value="<?php echo (isset($data['returnUrl']) && ($data['returnUrl'] !== '')?$data['returnUrl']:''); ?>">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">通讯密钥</label>
                    <div class="layui-input-block">
                        <input class="layui-input" name="key" placeholder="请输入通讯密钥" value="<?php echo (isset($data['key']) && ($data['key'] !== '')?$data['key']:''); ?>">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">区分方式</label>
                    <div class="layui-input-block">
                        <div class="layui-upload">
                            <input type="radio" name="payQf" value="1" <?php if($data['payQf'] == '1'): ?>checked<?php endif; ?> title="金额递增">
                            <input type="radio" name="payQf" value="2" <?php if($data['payQf'] == '2'): ?>checked<?php endif; ?> title="金额递减">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">微信码</label>
                    <div class="layui-input-block">
                        <div class="layui-upload">
                            <button type="button" class="layui-btn" id="wxup">上传收款二维码</button>（此处上传的是无金额的收款二维码）
                            <div class="layui-upload-list">
                                <img class="layui-upload-img" id="wximg" src= "/manage/Pay_modl/enQrcode?url=<?php echo $data['wxpay']; ?>">
                                <p id="wxcs"></p>
                                <input type="hidden" name="wxpay" value="<?php echo $data['wxpay']; ?>" id="wxpay">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">支付宝码</label>
                    <div class="layui-input-block">
                        <div class="layui-upload">
                            <button type="button" class="layui-btn" id="zfbup">上传收款二维码</button>（此处上传的是无金额的收款二维码）
                            <div class="layui-upload-list">
                                <img class="layui-upload-img" id="zfbimg" src="/manage/Pay_modl/enQrcode?url=<?php echo $data['zfbpay']; ?>">
                                <p id="zfbcs"></p>
                                <input type="hidden" name="zfbpay" value="<?php echo $data['zfbpay']; ?>" id="zfbpay">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-md6">
                <div class="layui-form-item">
                    <label class="layui-form-label">监控端状态</label>
                    <div class="layui-input-block">
                        <input class="layui-input" id="jkstate" readonly>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">最后心跳</label>
                    <div class="layui-input-block">
                        <input class="layui-input" id="lastheart"  readonly>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">最后支付</label>
                    <div class="layui-input-block">
                        <input class="layui-input" id="lastpay"  readonly>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">配置数据</label>
                    <div class="layui-input-block">
                        <input type="text" id="input" lay-verify="required" placeholder="手动配置数据" autocomplete="off" readonly class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">配置二维码</label>
                    <div class="layui-input-block">
                        <img id="pzqrcode">
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn" onclick="window.open('../v.apk')">下载监控端</button>
                    <button class="layui-btn" onclick="window.open('https://github.com/szvone/vmqApk/releases')">最新版监控端下载</button>
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
<script type="text/javascript" src="/static/manage/js/qrcode.js"></script>
<script>
    $.post("getSettings",function (data) {
        console.log( data )
        if (data.code==1){
            if (data.data.jkstate == -1){
                $("#jkstate").val("监控端未绑定，请您扫码绑定");
            }else if (data.data.jkstate == 0){
                $("#jkstate").val("监控端已掉线，请您检查App是否正常运行");
            }else if (data.data.jkstate == 1){
                $("#jkstate").val("运行正常");
            }
            $("#lastheart").val( data.data.lastheart);
            $("#lastpay").val( data.data.lastpay );
            var img = window.location.host+"/"+data.data.key;
            $("#input").val(img);
            $("#pzqrcode").attr("src","/manage/Pay_modl/enQrcode?url="+img);
        }
    });
    layui.use(['form','upload'], function() {
        var form = layui.form,upload=layui.upload;
        //微信二维码
        var uploadInst = upload.render({
            elem: '#wxup'
            ,url: '/upload/'
            ,auto: false
            ,choose: function(obj){
                obj.preview(function(index, file, result){
                    qrcode.decode(getObjectURL(file));
                    qrcode.callback = function(imgMsg) {
                        if(imgMsg!=""){
                            $('#wximg').attr('src', "/manage/Pay_modl/enQrcode?url="+imgMsg);
                        }else{
                            layer.msg('处理中', {
                                icon: 16
                                ,shade: 0.01
                                ,time:0
                            });
                            $.post("<?php echo url('pay_modl/process'); ?>","base64="+encodeURIComponent(result.split(",")[1]),function (data) {
                                if (!data.data) {
                                    data = JSON.parse(data);
                                }
                                if (data.code==1){
                                    $('#wximg').attr('src', "/manage/Pay_modl/enQrcode?url="+data.data);
                                    layer.msg('处理成功');
                                } else{
                                    return layer.msg('处理失败');
                                }
                            });
                        }
                    }
                });
            }
            , before: function (obj) {
                layer.msg('处理中', {
                    icon: 16
                    ,shade: 0.01
                    ,time:0
                });
            }
            , done: function (res) {
                //如果上传失败
                if (res.code == -1) {
                    return layer.msg('上传失败');
                }
                if (res.data==""){
                    return layer.msg('请上传微信无金额收款二维码');
                }

                layer.msg('处理成功');
                $('#wximg').attr('src', "/manage/Pay_modl/enQrcode?url="+res.data);
            }
            , error: function () {
                layer.msg('上传失败');
                //演示失败状态，并实现重传
                var demoText = $('#wxcs');
                demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs wxcs">重试</a>');
                demoText.find('.wxcs').on('click', function () {
                    uploadInst.upload();
                });
            }
        });
        //支付宝二维码
        var uploadInst2 = upload.render({
            elem: '#zfbup'
            , url: '/upload/'
            ,auto: false
            ,choose: function(obj){
                obj.preview(function(index, file, result){
                    qrcode.decode(getObjectURL(file));
                    qrcode.callback = function(imgMsg) {
                        if(imgMsg!=""){
                            $('#zfbimg').attr('src', "/manage/Pay_modl/enQrcode?url="+imgMsg);
                        }else{
                            layer.msg('处理中', {
                                icon: 16
                                ,shade: 0.01
                                ,time:0
                            });
                            $.post("<?php echo url('pay_modl/process'); ?>","base64="+encodeURIComponent(result.split(",")[1]),function (data) {
                                if (!data.data) {
                                    data = JSON.parse(data);
                                }
                                if (data.code==1){
                                    $('#zfbimg').attr('src', "/manage/Pay_modl/enQrcode?url="+data.data);
                                    layer.msg('处理成功');
                                } else{
                                    return layer.msg('处理失败');
                                }

                            });
                        }
                    }
                });

            }
            , before: function (obj) {
                layer.msg('处理中', {
                    icon: 16
                    ,shade: 0.01
                    ,time:0
                });
            }
            , done: function (res) {
                //如果上传失败
                if (res.code == -1) {
                    return layer.msg('上传失败');
                }
                if (res.data=="" ){
                    return layer.msg('请上传支付宝无金额收款二维码');
                }
                layer.msg('处理成功');
                $('#zfbimg').attr('src', "/manage/Pay_modl/enQrcode?url="+res.data);
            }
            , error: function () {
                layer.msg('上传失败');

                //演示失败状态，并实现重传
                var demoText = $('#zfbcs');
                demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs zfbcs">重试</a>');
                demoText.find('.zfbcs').on('click', function () {
                    uploadInst2.upload();
                });
            }
        });
        //表单提交
        form.on('submit(formDemo)', function(data){
            data.field.wxpay = $("#wximg").attr("src").replace(/\/manage\/Pay_modl\/enQrcode\?url=/g,"");
            data.field.zfbpay = $("#zfbimg").attr("src").replace(/\/manage\/Pay_modl\/enQrcode\?url=/g,"");
            $.post('<?php echo url("index"); ?>',data.field,function (res) {
                if( res.code === 1 ){
                    toastr.success( res.msg ,function () {
                        setTimeout( function () {
                            window.location.reload();
                        },1000)
                    });
                } else {
                    toastr.error( res.msg ,{icon: 2})
                }
            })
            return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
        });
    });
</script>