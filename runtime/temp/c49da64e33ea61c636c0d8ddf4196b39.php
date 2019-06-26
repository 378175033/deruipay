<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:84:"D:\phpStudy\PHPTutorial\WWW\F4\public/../application/manage\view\withdraw\index.html";i:1561025183;s:71:"D:\phpStudy\PHPTutorial\WWW\F4\application\manage\view\common\head.html";i:1560913706;}*/ ?>
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

<script type="text/html" id="status">
    <div>
        {{# if(d.status == 0){ }}
        <a href="javascript:openIframe({content:'check_status?id={{d.id}}&val=0',area:['30%','50%'],title:'提款审核'});" class="layui-btn layui-btn-xs layui-btn-danger" >待审核</a>
        {{# } else if( d.status == 1 ){ }}
        <a href="javascript:openIframe({content:'check_status?id={{d.id}}&val=1',area:['30%','50%'],title:'提款审核'});" class="layui-btn layui-btn-xs layui-btn-primary">未通过</a>
        {{# }else{  }}
        <a href="javascript:openIframe({content:'check_status?id={{d.id}}&val=2',area:['30%','50%'],title:'提款审核'});" class="layui-btn layui-btn-xs">通过</a>
        {{# } }}
    </div>
</script>

<script type="text/html" id="dele">
    <div>
        <a href="#" class="layui-btn layui-btn-xs layui-btn-danger btn-remove" data-id="{{ d.id }}" >删除
        </a>
    </div>
</script>


<form class="layui-form" action="">
    <div class="lay-all">
        <div class="layui-block" style="padding: 20px">
            <!-- 时间验证 -->
            <div class="layui-inline">
                <label class="layui-form-label" style="width: auto">时间范围查询</label>
                <div class="layui-inline">
                    <input class="layui-input test-item"  name="stime" placeholder="开始日" type="text">
                </div>
                <div class="layui-inline">
                    <input class="layui-input test-item" name="ltime" placeholder="截止日" type="text">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">流水号</label>
                <div class="layui-input-inline">
                    <input type="number" name="e-w_id" autocomplete="off" placeholder="请输入流水号" class="layui-input">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">商户名称</label>
                <div class="layui-input-inline">
                    <input type="text" name="l-b-name" autocomplete="off" placeholder="请输入商户名称" class="layui-input">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">审核状态</label>
                <div class="layui-input-inline">
                    <select name="e-status">
                        <option value=0>请选择</option>
                        <option value=1>未通过</option>
                        <option value=2>已通过</option>
                    </select>
                </div>
            </div>
            <!--<div class="layui-inline">-->
            <!--<label class="layui-form-label">用户ID</label>-->
            <!--<div class="layui-input-inline">-->
            <!--<input type="text" name="e-user_id" autocomplete="off" placeholder="请输入用户id" class="layui-input">-->
            <!--</div>-->
            <!--</div>-->
            <button class="layui-btn" lay-filter="query" lay-submit="">
                <i class="layui-icon layui-icon-search"></i>查询
            </button>
            <button class="layui-btn" lay-filter="clear" lay-submit="">
                <i class="layui-icon layui-icon-circle"></i>清空条件
            </button>
        </div>
    </div>
</form>
<table class="layui-hide" id="menu-table" lay-filter="text"></table>
</body>
<script type="text/javascript" src="/static/manage/js/jquery.js"></script>
<script type="text/javascript" src="/static/layui/layui.js"></script>
<script type="text/javascript" src="/static/manage/js/toastr/toastr.js"></script>
<script type="text/javascript" src="/static/manage/js/base.js"></script>
<script>
    layui.use(['form', 'laydate','table'], function() {
        var form = layui.form,
            laydate = layui.laydate,
            table = layui.table;
        //渲染时间选择器
        lay('.test-item').each(function() {
            laydate.render({
                elem: this,
                trigger: 'click'
            });
        });



        //监听查询提交
        form.on('submit(query)', function(data) {
            console.log( data )
            table.reload('LogList', {
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
            elem: '#menu-table',
            method: 'post',
            url: '<?php echo url("index"); ?>',
            limit: 10,
            id:'LogList',
            limits: [5,10,15,20,50,100], //每页条数的选择项
            loading: true,
            where:{ order:'id desc'},
            cellMinWidth: 80, //全局定义常规单元格的最小宽度，layui 2.2.1 新增
            cols: [
                [
                    {field: 'id',title: 'ID'},
                    {field: 'bus_id',title: '商户名称'},
                    {field: 'money',title: '申请提款金额'},
                    {field: 'w_id',title: '流水号'},
                    {field: 'create_time',title: '商户发起时间'},
                    {field: 'check_desc',title: '审核描述'},
                    {field: 'status',title: '审核',toolbar:'#status'},
                    {field: 'opera',title: '操作',toolbar:'#dele'},
                ]
            ],
            page: true,

            response: {
                statusCode: "1" //重新规定成功的状态码为 200，table 组件默认为 0
            },
            parseData: function(res){ //res 即为原始返回的数据
                console.log( res );
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