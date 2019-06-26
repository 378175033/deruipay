<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:82:"D:\phpStudy\PHPTutorial\WWW\F4\public/../application/manage\view\notice\index.html";i:1561025183;s:71:"D:\phpStudy\PHPTutorial\WWW\F4\application\manage\view\common\head.html";i:1560913706;}*/ ?>
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
<div class="layui-card">
    <div class="layui-card-header">
        <a class="layui-btn" href="javascript:openIframe({'title':'添加公告'});"> <i class="layui-icon layui-icon-add-1"></i>新增公告</a>
    </div>
    <div class="layui-card-body">
        <table class="layui-hide" id="table" lay-filter="text"></table>
    </div>
</div>
<script type="text/html" id="barDemo">
    <a href="javascript:openIframe({content:'edit.html?id={{d.id}}',title:'更新公告',area:['90%','90%']});" class="layui-btn layui-btn-xs layui-btn-normal" title="修改数据">
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
        {{#  if(d.is_top == 1){ }}
        <a class="layui-btn layui-btn-normal layui-btn-xs top-toggle" data-field="is_top" data-value="0" data-id="{{ d.id }}">置顶</a>
        {{#  } else { }}
        <a class="layui-btn layui-btn-danger layui-btn-xs top-toggle" data-field="is_top" data-value="1" data-id="{{ d.id }}">未置顶</a>
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
    $(document).on( 'click','.top-toggle',function () {
        var val = $(this).data("value");
        var id = $(this).data("id");
        var that = $(this);
        $.post('isTop',{id:id,value:val},function ( res ) {
            if( res.code ===  1 ){
                toastr.success( res.msg ,function () {
                    if( val === 1 ){
                        that.removeClass('layui-btn-danger').addClass('layui-btn-normal');
                        that.html('置顶');
                        that.data('value', 0)
                    } else {
                        that.removeClass('layui-btn-normal').addClass('layui-btn-danger');
                        that.html('未置顶');
                        that.data('value', 1)
                    }
                })
            } else {
                toastr.error( res.msg )
            }
        })
    })
    layui.use(['table'], function() {
        var table = layui.table;
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
                    {field: 'name',title: '公告名称'},
                    {field: 'desc',title: '公告简介'},
                    {field: 'view',title: '公告浏览次数'},
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