<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:85:"D:\phpStudy\PHPTutorial\WWW\F4\public/../application/business\view\index\welcome.html";i:1562902453;s:73:"D:\phpStudy\PHPTutorial\WWW\F4\application\business\view\common\head.html";i:1561025183;}*/ ?>
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
<style>
    .bgf {
        background: #fff;
    }
    .clearfix {
        zoom: 1;
    }
    .m30{
        margin: 30px 2%;
        overflow: hidden;
    }
    .layui-card-header{
        padding: .75rem 1.25rem;
        margin-bottom: 0;
        background-color: rgba(15,15,15,0.2);
        border-bottom: 1px solid rgba(0,0,0,.125);
    }
    .h4{
        font-size: 1.4rem;
    }
    .bulletin li{
       padding: 20px;
    }
    .bulletin span{
        font-size: 1.1rem;
    }
    .bg-violet{
        background: #796AEE !important;
    }
    .bg-violet,.bg-red,.bg-orange,.bg-cash{

        color: #fff;
        width: 40px;
        height: 40px;
        line-height: 40px;
        text-align: center;
        min-width: 40px;
        max-width: 40px;
        border-radius: 50%;
        display:inline-block;
        float: left;
    }
    .bg-red{
        background: #ff7676 !important;
    }
    .bg-orange{
        background: #ffc36d !important;
    }
    .bg-cash{
        background: #FF5722 !important;
    }
    .title{
        font-size: 1.3em;
        font-weight: 300;
        color: #777;
        margin: 0 20px;
        float: left;
    }
    .number {
        font-size: 1.8em;
        line-height: 46px;

    }
    .fl{
        height: 142px;
        width: 100%;
        padding-right: 15px;
        padding-left: 15px;
        margin-right: auto;
        margin-left: auto;
    }
    .layui-progress{
        margin-top: 10px;
    }
    .section{
        border-right: 1px solid #eee;
    }
    .layui-col-md3{
        padding: 0 20px;
        height: 80px;
        margin-top: 31px;
    }
    .bg{
        margin-top: 15px;
     }
    body .layer-ext-yourskin .layui-layer-title{
        border-bottom: 1px solid #eee;
    }
    body .layui-ext-yourskin .layui-layer-btn{}
    body .layui-ext-yourskin .layui-layer-btn a{}
</style>

<div class="clearfix bgf m30">
    <div class="fl">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md3 section">
                <div class="bg">
                    <div class="icon bg-violet"><i class="layui-icon layui-icon-username"></i></div>
                    <div class="title">账户余额
                        <div class="layui-progress">
                            <div class="layui-progress-bar" lay-percent="50%"></div>
                        </div>
                    </div>

                    <div class="number"><?php echo $user['money']; ?></div>
                </div>

            </div>
            <div class="layui-col-md3 section">
                <div class="bg">
                    <span class="icon bg-red"><i class="layui-icon layui-icon-log"></i></span>
                    <div class="title">冻结余额
                        <div class="layui-progress">
                            <div class="layui-progress-bar layui-bg-red" lay-percent="30%"></div>
                        </div>
                    </div>
                    <div class="number"><?php echo $frozen_money; ?></div>
                </div>
            </div>
            <div class="layui-col-md3">
                <div class="bg">
                    <div class="icon bg-orange"><i class="layui-icon layui-icon-log"></i></div>
                    <div class="title">投资保证金
                        <div class="layui-progress">
                            <div class="layui-progress-bar layui-bg-orange" lay-percent="60%"></div>
                        </div>
                    </div>
                    <div class="number">0.00</div>
                </div>
            </div>
            <div class="layui-col-md3">
                <div class="bg">
                    <div class="icon bg-cash"><i class="layui-icon layui-icon-rmb"></i></div>
                    <div class="title">可提现金额
                        <div class="layui-progress">
                            <div class="layui-progress-bar layui-bg-green" lay-percent="30%"></div>
                        </div>
                    </div>
                    <div class="number">¥ <?php echo $user['money']; ?>
                        <a  href="javascript:openIframe({title:'余额提现申请',content:'/business/user/withdraw',area:['600px','400px']});" class="layui-btn layui-btn-danger">提现</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div style="padding: 20px; background-color: #F2F2F2;">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md4">
            <div class="layui-card">
                <div class="layui-card-header"><h3 class="h4">平台公告</h3></div>
                <div class="layui-card-body">
                   <ul class="bulletin">
                       <?php if(is_array($notices) || $notices instanceof \think\Collection || $notices instanceof \think\Paginator): $i = 0; $__LIST__ = $notices;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$notice): $mod = ($i % 2 );++$i;?>
                           <li>
                               <a href="javascript:;" class="layui-btns">
                                   <i class="layui-icon layui-icon-form"></i>
                                   <span><?php echo $notice['name']; ?></span>
                                   <div class="hide" style="display: none">
                                       <?php echo $notice['content']; ?>
                                   </div>
                               </a>
                           </li>
                       <?php endforeach; endif; else: echo "" ;endif; ?>
                   </ul>
                </div>
            </div>
        </div>
        <div class="layui-col-md4">
            <div class="layui-card">
                <div class="layui-card-header"><h3 class="h4">对接资源</h3></div>
                <div class="layui-card-body">
                    <ul class="bulletin">
                        <li>
                            <a href="">
                                <i class="layui-icon layui-icon-about"></i>
                                <span>点击：在线阅读开发文档</span>
                            </a>
                        </li>
                        <li>
                            <a href="">
                                <i class="layui-icon layui-icon-about"></i>
                                <span>点击：下载开发文档</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="layui-col-md4">
            <div class="layui-card">
                <div class="layui-card-header"><h3 class="h4">登录信息</h3></div>
                <div class="layui-card-body">
                    <ul class="bulletin">
                        <li>
                            <a>
                                <i class="layui-icon layui-icon-form"></i>
                                <span>上次登录ip:<?php echo $logs['ip']; ?></span>
                            </a>
                        </li>
                        <li>
                            <a>
                                <i class="layui-icon layui-icon-form"></i>
                                <span>登录地址：<?php echo $logs['address']; ?></span>
                            </a>
                        </li>
                        <li>
                            <a>
                                <i class="layui-icon layui-icon-form"></i>
                                <span>登录时间：<?php echo $logs['create_time']; ?></span>
                            </a>
                        </li>
                    </ul>
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
    layui.use('element', function(){
        var element = layui.element;
    });
    layui.use('layer', function(){ //独立版的layer无需执行这一句
        $('.layui-btns').on('click', function(){
            layer.open({
                skin: 'layer-ext-yourskin',
                title:$(this).children('span').text(),
                type: 1,
                area:['780px','630px'],
                content: $(this).find('.hide').html(),  //这里content是一个URL，如果你不想让iframe出现滚动条，你还可以content: ['http://sentsin.com', 'no']
            });
        });

    });
</script>