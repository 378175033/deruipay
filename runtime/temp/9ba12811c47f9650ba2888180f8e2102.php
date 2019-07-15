<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:86:"D:\phpStudy\PHPTutorial\WWW\F4\public/../application/business\view\order\withdraw.html";i:1562901960;s:73:"D:\phpStudy\PHPTutorial\WWW\F4\application\business\view\common\head.html";i:1561025183;}*/ ?>
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
    .layui-table-cell{
        height: auto;
        line-height: 21px;
    }
</style>
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
            <button class="layui-btn" lay-filter="query" lay-submit="">
                <i class="layui-icon layui-icon-search"></i>查询
            </button>
            <button class="layui-btn" lay-filter="clear" lay-submit="">
                <i class="layui-icon layui-icon-circle"></i>清空条件
            </button>
        </div>
    </div>
</form>
<script type="text/html" id="status">
    <div>
        {{# if(d.status == 0){ }}
        <a class="layui-btn layui-btn-xs layui-btn-danger" >待审核</a>
        {{# } else if( d.status == 1 ){ }}
        <a class="layui-btn layui-btn-xs layui-btn-warm">未通过</a>
        {{# }else{  }}
        <a class="layui-btn layui-btn-xs">通过</a>
        {{# } }}
    </div>
</script>
<table class="layui-hide" id="menu-table" lay-filter="text"></table>
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
            table.reload('LogList', {
                url: '<?php echo url("withdraw"); ?>',
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
            url: '<?php echo url("withdraw"); ?>',
            limit: 10,
            id:'LogList',
            limits: [5,10,15,20,50,100], //每页条数的选择项
            loading: true,
            where:{ order:'id desc' },
            cellMinWidth: 80, //全局定义常规单元格的最小宽度，layui 2.2.1 新增
            cols: [
                [
                    {field: 'id',title: 'ID'},
                    {field: 'money',title: '申请提款金额'},
                    {field: 'w_id',title: '提现账户信息',templet:function (d) {
                            var type = d.w_id;

                            var str ="";
                            switch (type) {
                                case "1":
                                    str = "支付宝账号："+d.ali_name+'<br>到账用户：'+d.ali_user;
                                    break;
                                case "2":
                                    str = "微信账号："+d.we_name+'<br>到账用户：'+d.we_user;
                                    break;
                                case "3":
                                    str = "银行卡账号："+d.un_name+'<br>银行名称：'+d.un_bank+"<br>开户支行："+d.un_branch+'<br>到账用户：'+d.un_user;
                                    break;
                                default:
                                    break;
                            }
                            return str;
                        }
                    },
                    {field: 'create_time',title: '发起时间'},
                    {field: 'check_desc',title: '审核描述'},
                    {field: 'status',title: '审核',toolbar:'#status'}
                ]
            ],
            page: true,

            response: {
                statusCode: "1" //重新规定成功的状态码为 200，table 组件默认为 0
            },
            parseData: function(res){ //res 即为原始返回的数据
                console.log( res )
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