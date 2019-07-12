<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:83:"D:\phpStudy\PHPTutorial\WWW\F4\public/../application/manage\view\recycle\index.html";i:1561360432;s:71:"D:\phpStudy\PHPTutorial\WWW\F4\application\manage\view\common\head.html";i:1561360432;}*/ ?>
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
<table class="layui-hide" id="menu-table" lay-filter="text"></table>
<form class="layui-form">
    <a href="javascript:;" class="layui-btn layui-btn-xs" lay-filter="reduction" lay-submit="">还原选中项</a>
    <a href="javascript:;" class="layui-btn layui-btn-xs" lay-filter="delete" lay-submit="">删除选中项</a>
</form>
<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-xs layui-btn-danger btn-delete" data-id="{{ d.id }}" title="彻底删除">
        <i class="layui-icon layui-icon-delete"></i>
    </a>
    <a class="layui-btn layui-btn-xs btn-reduction" data-id="{{ d.id }}" title="数据还原">
        <i class="layui-icon layui-icon-refresh"></i>
    </a>
</script>
<script type="text/javascript" src="/static/manage/js/jquery.js"></script>
<script type="text/javascript" src="/static/layui/layui.js"></script>
<script type="text/javascript" src="/static/manage/js/toastr/toastr.js"></script>
<script type="text/javascript" src="/static/manage/js/base.js"></script>
<script>
    //数据还原
    $(document).on('click','.btn-reduction',function () {
        var id = $(this).data('id');
        var that = $(this).parents('tr');
        $.post('reduction',{id:id},function (res) {
            if( res.code === 1 ){
                toastr.success( res.msg, function () {
                    that.remove();
                })
            } else {
                toastr.error( res.msg )
            }
        })
    })

    layui.use(['table','form'], function() {
        var table = layui.table,form = layui.form;
        form.on('submit(reduction)', function() {
            var checkStatus = table.checkStatus( 'menu-table' ); //idTest 即为基础参数 id 对应的值
            var data = checkStatus.data;
            var len = checkStatus.data.length;
            //判断是否有选中项
            if( len < 1){
                toastr.error("请至少选中一项！");
                return false;
            }
            var param = new Array();
            for( var i = 0; i< len; i++){
                param.push( data[i].id );
            }
            $.post( "reduction", {id:param},function (res) {
                if( res.code === 1){
                    toastr.success( res.msg )
                } else {
                    toastr.error( res.msg )
                }
            },'json')
            table.reload('menu-table', {
                url: '<?php echo url("index"); ?>',
                where: data.field
            });
            return false;
        });
        form.on('submit(delete)', function() {
            var checkStatus = table.checkStatus( 'menu-table' ); //idTest 即为基础参数 id 对应的值
            var data = checkStatus.data;
            var len = checkStatus.data.length;
            //判断是否有选中项
            if( len < 1){
                toastr.error("请至少选中一项！");
                return false;
            }
            layer.confirm("您确定要删除吗？无法找回哟！",function () {
                var param = new Array();
                for( var i = 0; i< len; i++){
                    param.push( data[i].id );
                }
                $.post( "delete", {id:param},function (res) {
                    if( res.code === 1){
                        toastr.success( res.msg )
                    } else {
                        toastr.error( res.msg )
                    }
                },'json')
                table.reload('menu-table', {
                    url: '<?php echo url("index"); ?>',
                    where: data.field
                });
            })
            return false;
        });

        //表格数据渲染
        table.render({
            elem: '#menu-table',
            method: 'post',
            url: '<?php echo url("index"); ?>',
            limit: 10,
            toolbar: true,
            limits: [5,10,15,20,50,100], //每页条数的选择项
            loading: true,
            param: {'parent_id':0},
            cellMinWidth: 80, //全局定义常规单元格的最小宽度，layui 2.2.1 新增
            cols: [
                [
                    {checkbox: true},
                    {field: 'id',title: 'ID'},
                    {field: 'table',title: '来源表单'},
                    {field: 'desc',title: '描述信息'},
                    {field: 'rid',title: '数据编号'},
                    {field: 'create_time',title: '添加时间',sort: true},
                    {field: 'operation',title: '操作',toolbar: '#barDemo'}
                ]
            ],
            page: true,
            response: {
                statusCode: "1" //重新规定成功的状态码为 200，table 组件默认为 0
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