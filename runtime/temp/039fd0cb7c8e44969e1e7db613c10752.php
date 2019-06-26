<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:84:"D:\phpStudy\PHPTutorial\WWW\F4\public/../application/manage\view\business\index.html";i:1561025183;s:71:"D:\phpStudy\PHPTutorial\WWW\F4\application\manage\view\common\head.html";i:1560913706;}*/ ?>
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
<style type="text/css">
    .layui-table-cell {
        height: 42px;
        line-height: 42px;
    }
</style>
<div class="layui-card">
    <div class="layui-card-header">
        <a class="layui-btn" href="javascript:openIframe({'title':'添加商户'});"> <i class="layui-icon layui-icon-add-1"></i>新增商户</a>
    </div>
    <form class="layui-form" action="">
        <div class="lay-all">
            <div class="layui-block" style="padding: 20px">
                <div class="layui-inline">
                    <label class="layui-form-label">商户名</label>
                    <div class="layui-input-inline">
                        <input type="text" name="l-name" autocomplete="off" placeholder="请输入商户名" class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">登录名</label>
                    <div class="layui-input-inline">
                        <input type="text" name="l-login_name" autocomplete="off" placeholder="请输入登录名" class="layui-input">
                    </div>
                </div>

                <div class="layui-inline">
                    <label class="layui-form-label">手机号</label>
                    <div class="layui-input-inline">
                        <input type="text" name="l-mobile" autocomplete="off" placeholder="请输入手机号" class="layui-input">
                    </div>
                </div>

                <button class="layui-btn" lay-filter="query" lay-submit="">
                    <i class="layui-icon layui-icon-search"></i>查询
                </button>
                <button class="layui-btn" lay-filter="clear" lay-submit="">
                    <i class="layui-icon layui-icon-circle"></i>清空条件
                </button>
            </div>
        </div>
    </form>
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
</script>
<script type="text/html" id="status">
    <div>
        {{#  if(d.status == 1){ }}
        <a class="layui-btn layui-btn-normal layui-btn-xs status-toggle" data-value="0" data-id="{{ d.id }}">显示</a>
        {{#  } else { }}
        <a class="layui-btn layui-btn-danger layui-btn-xs status-toggle" data-value="1" data-id="{{ d.id }}">隐藏</a>
        {{#  } }}
        {{# if(d.check == 0){ }}
            <a href="javascript:openIframe({content:'check?id={{d.id}}&val=0',area:['30%','50%'],title:'商家审核'});" class="layui-btn layui-btn-xs layui-btn-danger" >待审核</a>
        {{# } else if( d.check == 1 ){ }}
            <a href="javascript:openIframe({content:'check?id={{d.id}}&val=1',area:['30%','50%'],title:'商家审核'});" class="layui-btn layui-btn-xs layui-btn-primary">未通过</a>
        {{# }else{  }}
            <a href="javascript:openIframe({content:'check?id={{d.id}}&val=2',area:['30%','50%'],title:'商家审核'});" class="layui-btn layui-btn-xs">通过</a>
        {{# } }}
    </div>
</script>
<script type="text/html" id="sort">
    <input type="number" class="layui-input sort-order" style="height: 28px" value="{{ d.sort }}" data-id="{{d.id}}">
</script>
<script type="text/html" id="money">
    <div style="width: 180px;line-height: 21px;float: left">
        商户余额：<span class="layui-bg-green">{{ d.money }}</span>
        <br/>
        冻结金额：<span class="layui-bg-red">{{ d.frozen_money }}</span>
    </div>
    <a href="javascript:openIframe({content:'account.html?id={{d.id}}&type=1',title:'设置商户-{{d.name}}余额',area:['30%','50%']});" class="layui-btn layui-btn-xs layui-btn-warm" title="设置商户余额"><i class="layui-icon">&#xe659;</i></a>
    <a href="javascript:openIframe({content:'account.html?id={{d.id}}&type=2',title:'设置商户-{{d.name}}冻结金额',area:['30%','50%']});" class="layui-btn layui-btn-xs" title="设置商户冻结金额"><i class="layui-icon">&#xe659;</i></a>
    <a href="javascript:openIframe({content:'account_log.html?id={{d.id}}',title:'查看商户-{{d.name}}余额明细',area:['80%','80%']});" class="layui-btn layui-btn-xs layui-btn-primary" title="查看商户余额变动明细"><i class="layui-icon">&#xe656;</i></a>
    <a href="javascript:openIframe({content:'passageway.html?id={{d.id}}',title:'查看商户-{{d.name}}通道',area:['80%','80%']});" class="layui-btn layui-btn-xs layui-btn-danger" title="查看商户通道"><i class="layui-icon">&#xe663;</i></a>
</script>
<script type="text/javascript" src="/static/manage/js/jquery.js"></script>
<script type="text/javascript" src="/static/layui/layui.js"></script>
<script type="text/javascript" src="/static/manage/js/toastr/toastr.js"></script>
<script type="text/javascript" src="/static/manage/js/base.js"></script>
<script>
    layui.use(['form', 'laydate','table'], function() {
        var form = layui.form,
            laydate = layui.laydate,
            table = layui.table;
        //监听查询提交
        form.on('submit(query)', function(data) {
            table.reload('table', {
                url: '<?php echo url("index"); ?>',
                where: data.field
            });
            return false;
        });

        form.on('submit(clear)', function() {
            window.refresh();
            return false;
        });
        //表格数据渲染
        table.render({
            elem: '#table',
            method: 'post',
            url: '<?php echo url("index"); ?>',
            limit: 9999,
            page : true,
            limits: [5,10,15,20,50,100], //每页条数的选择项
            // limits: false, //每页条数的选择项
            loading: true,
            param: {},
            cellMinWidth: 80, //全局定义常规单元格的最小宽度，layui 2.2.1 新增
            //count: 50,
            initSort: {
                field: 'sort',
                type: 'asc'
            },
            cols: [
                [
                    {field: 'id',title: 'ID'},
                    {field: 'login_name',title: '登录名'},
                    {field: 'name',title: '商户名'},
                    {field: 'mobile',title: '手机号码'},
                    {field: 'last_login_time',title: '商户最近登录时间'},
                    {field: 'money',title: '商户余额',templet: '#money',width:400},
                    {field: 'sort',title: '排序',templet:'#sort'},
                    {field: 'status',title: '状态',templet: '#status'},
                    {field: 'create_time',title: '添加时间'},
                    {field: 'update_time',title: '修改时间'},
                    {field: 'operation',title: '操作',toolbar: '#barDemo'}
                ]
            ],
            response: {
                statusCode: "1" //重新规定成功的状态码为 200，table 组件默认为 0
            },
            done: function(){

            },
            parseData: function(res){ //res 即为原始返回的数据
                // console.log( res )
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
</html>