<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:80:"D:\phpStudy\PHPTutorial\WWW\F4\public/../application/manage\view\menu\index.html";i:1560827637;s:71:"D:\phpStudy\PHPTutorial\WWW\F4\application\manage\view\common\head.html";i:1560913706;}*/ ?>
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
        <a class="layui-btn" href="javascript:openIframe({'title':'添加菜单'});"> <i class="layui-icon layui-icon-add-1"></i>新增菜单</a>
    </div>
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
    <a href="javascript:openIframe({content:'add?pid={{d.id}}'});" class="layui-btn layui-btn-xs layui-btn-warm" data-id="{{ d.id }}" title="添加子菜单">
        <i class="layui-icon layui-icon-add-1"></i>
    </a>
</script>
<script type="text/html" id="status">
    <div>
        {{#  if(d.status == 1){ }}
        <a class="layui-btn layui-btn-normal layui-btn-xs status-toggle" data-value="0" data-id="{{ d.id }}">显示</a>
        {{#  } else { }}
        <a class="layui-btn layui-btn-danger layui-btn-xs status-toggle" data-value="1" data-id="{{ d.id }}">隐藏</a>
        {{#  } }}
    </div>
</script>
<script type="text/html" id="sort">
    <input type="number" class="layui-input sort-order" style="height: 28px" value="{{ d.sort }}" data-id="{{d.id}}">
</script>
<script type="text/html" id="tree">
    {{#  if(d.parent_id == 0){ }}
    <span class="node node-{{ d.parent_id }}" data-id="{{ d.id }}" style="width: 100%;display: inline-block;cursor: pointer"><i class="layui-icon layui-icon-triangle-r"></i>{{ d.id }}</span>
    {{#  } else { }}
    <span class="node child-node node-{{ d.parent_id }}" data-id="{{ d.id }}" style="width: 100%;display: inline-block;cursor: pointer"><i class="layui-icon layui-icon-triangle-r"></i>{{ d.id }}</span>
    {{#  } }}
</script>
<script type="text/javascript" src="/static/manage/js/jquery.js"></script>
<script type="text/javascript" src="/static/layui/layui.js"></script>
<script type="text/javascript" src="/static/manage/js/toastr/toastr.js"></script>
<script type="text/javascript" src="/static/manage/js/base.js"></script>
<script>
    $(document).on('click','.node',function () {
        var pd = parseInt( $(this).css('padding-left') );
        var tr = $(this).parents('tr');
        var trs = $(".node-"+$(this).data('id')).parents('tr');
        var len = trs.length;

        if( trs.is(':visible') ){
            trs.hide();
            for( var i =0; i < len; i++){
                $(".node-"+trs.eq(i).find('.node').data('id') ).parents('tr').hide();
            }
            return '';
        }
        $(".node-"+$(this).data('id')).css('padding-left', pd+20+'px');
        trs.show();
        tr.after( trs );
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
            // limits: [5,10,15,20,50,100], //每页条数的选择项
            limits: false, //每页条数的选择项
            loading: true,
            param: {},
            cellMinWidth: 80, //全局定义常规单元格的最小宽度，layui 2.2.1 新增
            //count: 50,
            initSort: {
                field: 'id',
                type: 'asc'
            },
            cols: [
                [
                    {field: 'id',title: 'ID',templet:'#tree'},
                    {field: 'name',title: '菜单名称'},
                    {field: 'controller',title: '控制器名称'},
                    {field: 'action',title: '方法名称'},
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
                $('.child-node').parents('tr').hide();
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