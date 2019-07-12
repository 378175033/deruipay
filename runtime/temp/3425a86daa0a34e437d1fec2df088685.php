<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:81:"D:\phpStudy\PHPTutorial\WWW\F4\public/../application/manage\view\banks\index.html";i:1562754505;s:71:"D:\phpStudy\PHPTutorial\WWW\F4\application\manage\view\common\head.html";i:1561360432;}*/ ?>
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
<div class="layui-card">
    <div class="layui-card-header">
        <a class="layui-btn" href="javascript:openIframe({'title':'添加银行'});"> <i class="layui-icon layui-icon-add-1"></i>新增银行</a>
    </div>
    <!--<form class="layui-form" action="">-->
        <!--<div class="lay-all">-->
            <!--<div class="layui-block" style="padding: 20px;">-->
                <!--<div class="layui-inline">-->
                    <!--<label class="layui-form-label">银行名称</label>-->
                    <!--<div class="layui-input-inline">-->
                        <!--<input type="text" name="business_name" autocomplete="off" placeholder="请输入银行名称"-->
                               <!--class="layui-input">-->
                    <!--</div>-->
                <!--</div>-->
                <!--<button class="layui-btn" lay-filter="query" lay-submit="">-->
                    <!--<i class="layui-icon layui-icon-search"></i>查询-->
                <!--</button>-->
                <!--<button class="layui-btn" lay-filter="clear" lay-submit="">-->
                    <!--<i class="layui-icon layui-icon-circle"></i>清空条件-->
                <!--</button>-->
            <!--</div>-->
        <!--</div>-->
    <!--</form>-->
    <div class="layui-card-body">
        <table class="layui-hide" id="table" lay-filter="text"></table>
    </div>
</div>
<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-xs layui-btn-normal btn-update" data-id="{{ d.id }}" title="修改数据">
        <i class="layui-icon layui-icon-edit"></i>
    </a>
    <a class="layui-btn layui-btn-xs layui-btn-danger btn-remove" data-id="{{ d.id }}" title="移除数据">
        <i class="layui-icon layui-icon-delete"></i>
    </a>
    {{# if (d.is_free == 0 ){  }}
    <a href="javascript:openIframe({title: '添加{{d.name}}二维码',content: 'free_pay?type={{d.pay_type}}' });" class="layui-btn layui-btn-xs layui-btn-primary" data-id="{{ d.id }}" title="添加{{d.name}}二维码">
        <i class="layui-icon layui-icon-add-circle"></i>
    </a>
    <a href="javascript:openIframe({title: '查看{{d.name}}二维码',content: 'free_pay_list?type={{d.pay_type}}' });" class="layui-btn layui-btn-xs layui-btn-warm" data-id="{{ d.id }}" title="查看{{d.name}}二维码">
        <i class="layui-icon layui-icon-list"></i>
    </a>
    {{# } }}
</script>
<script type="text/html" id="status">
    <div>
        {{#  if(d.status == 1){ }}
        <a class="layui-btn layui-btn-normal layui-btn-xs status-toggle" data-value="0" data-id="{{ d.id }}" data-notice="禁用|启用">启用</a>
        {{#  } else { }}
        <a class="layui-btn layui-btn-danger layui-btn-xs status-toggle" data-value="1" data-id="{{ d.id }}" data-notice="禁用|启用">禁用</a>
        {{#  } }}
    </div>
</script>
<script type="text/html" id="sort">
    <input type="number" class="layui-input sort-order" style="height: 28px" value="{{ d.sort }}" data-id="{{d.id}}">
</script>
<script type="text/javascript" src="/static/manage/js/jquery.js"></script>
<script type="text/javascript" src="/static/layui/layui.js"></script>
<script type="text/javascript" src="/static/manage/js/toastr/toastr.js"></script>
<script type="text/javascript" src="/static/manage/js/base.js"></script>
<script>
    layui.use(['form','table'], function() {
        var table = layui.table;
        //监听查询提交

        //表格数据渲染
        table.render({
            elem: '#table',
            method: 'post',
            url: '<?php echo url("index"); ?>',
            limit: 10,
            page : true,
            limits: [10,15,20,50,100], //每页条数的选择项
            // limits: false, //每页条数的选择项
            loading: true,
            param: {},
            cellMinWidth: 80, //全局定义常规单元格的最小宽度，layui 2.2.1 新增
            //count: 50,
            cols: [
                [
                    {field: 'id',title: 'ID'},
                    {field: 'name',title: '银行名称'},
                    // {field: 'pay_type',title: '银行图片'},
                    {field: 'type',title: '银行类别'},
                    {field: 'status',title: '是否可用',templet: '#status'},
                    {field: 'operation',title: '操作',toolbar: '#barDemo',minWidth: 400}
                ]
            ],
            response: {
                statusCode: "1" //重新规定成功的状态码为 200，table 组件默认为 0
            },
            done: function(){

            },
            parseData: function(res){ //res 即为原始返回的数据
                return {
                    "code": res.code, //解析接口状态
                    "msg": res.msg, //解析提示文本
                    "count": res.data.count, //解析数据长度
                    "data": res.data.list//解析数据列表
                };
            }
        });
    })
</script>