<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:81:"D:\phpStudy\PHPTutorial\WWW\F4\public/../application/manage\view\order\index.html";i:1563242933;s:71:"D:\phpStudy\PHPTutorial\WWW\F4\application\manage\view\common\head.html";i:1563242933;}*/ ?>
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
        padding: 9px 5px;
        width: 60px;
    }
    .layui-inline{
        padding: 5px 2px;
    }
</style>
<form class="layui-form" action="">
    <div class="lay-all">
        <div class="layui-block" style="padding: 20px">
            <!-- 时间验证 -->
            <div class="layui-container" style="width: 100%">
                <div class="layui-row">
                    <div class="layui-col-xs3 layui-col-md2 layui-col-sm3">
                        <div class="layui-inline">
                            <!--<label class="layui-form-label">商户名称</label>-->
                            <div class="layui-input-inline">
                                <input type="text" name="business_name" autocomplete="off" placeholder="请输入商户名称"
                                       class="layui-input">
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-xs3 layui-col-md2">
                        <div class="layui-inline">
                            <!--<label class="layui-form-label">订单编号</label>-->
                            <div class="layui-input-inline">
                                <input type="text" name="e-order_id" autocomplete="off" placeholder="请输入交易流水号" class="layui-input">
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-xs3 layui-col-md2">
                        <div class="layui-inline">
                            <!--<label class="layui-form-label">通道名称</label>-->
                            <div class="layui-input-inline">
                                <select name="passageway_id" lay-search="">
                                    <option value="">选取通道</option>
                                    <?php if(is_array($passageway_list) || $passageway_list instanceof \think\Collection || $passageway_list instanceof \think\Paginator): $i = 0; $__LIST__ = $passageway_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$passageway): $mod = ($i % 2 );++$i;?>
                                    <option value="<?php echo $passageway['id']; ?>"><?php echo $passageway['name']; ?></option>
                                    <?php endforeach; endif; else: echo "" ;endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-xs3 layui-col-md2">
                        <div class="layui-inline">
                            <!--<label class="layui-form-label">状态</label>-->
                            <div class="layui-input-inline">
                                <select name="e-status">
                                    <option value="">请选择一个状态</option>
                                    <option value="0">审核中</option>
                                    <option value="1">成功</option>
                                    <option value="2">未提交</option>
                                    <option value="3">已提交</option>
                                    <option value="4">代付失败</option>
                                    <option value="5">提交失败</option>
                                    <option value="5">已退回</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!--<div class="layui-col-xs3 layui-col-md2">-->
                        <div class="layui-inline">
                            <button class="layui-btn" lay-filter="query" lay-submit="" onclick="$('#defaulttime').val('today')">
                                <i class="layui-icon layui-icon-search"></i>查询今日
                            </button>
                            <button class="layui-btn" lay-filter="query" lay-submit="" onclick="$('#defaulttime').val('week')">
                                <i class="layui-icon layui-icon-search"></i>查询本周
                            </button>
                        </div>
                    <!--</div>-->
                    <!--<div class="layui-col-xs3 layui-col-md2">-->
                        <div class="layui-inline">
                            <button class="layui-btn" lay-filter="query" lay-submit="" onclick="$('#defaulttime').val('month')">
                                <i class="layui-icon layui-icon-search"></i>查询本月
                            </button>
                            <button class="layui-btn" lay-filter="query" lay-submit="" onclick="$('#defaulttime').val('year')">
                                <i class="layui-icon layui-icon-search"></i>查询今年
                            </button>
                        </div>
                    <!--</div>-->
                    <!--<div class="layui-col-xs3 layui-col-md2">-->
                        <div class="layui-inline">
                            <button class="layui-btn" lay-filter="query" lay-submit="">
                                <i class="layui-icon layui-icon-search"></i>查询
                            </button>
                            <button class="layui-btn" lay-filter="clear" lay-submit="">
                                <i class="layui-icon layui-icon-circle"></i>清空条件
                            </button>
                        </div>
                    <!--</div>-->
                </div>
            </div>



            <!--<label class="layui-form-label" style="width: auto">时间范围查询</label>-->
            <!--<div class="layui-inline">-->
                <!--<button class="layui-btn" lay-filter="query" lay-submit="" onclick="$('#defaulttime').val('today')">-->
                    <!--<i class="layui-icon layui-icon-search"></i>查询今日-->
                <!--</button>-->
                <!--<button class="layui-btn" lay-filter="query" lay-submit="" onclick="$('#defaulttime').val('week')">-->
                    <!--<i class="layui-icon layui-icon-search"></i>查询本周-->
                <!--</button>-->
                <!--<button class="layui-btn" lay-filter="query" lay-submit="" onclick="$('#defaulttime').val('month')">-->
                    <!--<i class="layui-icon layui-icon-search"></i>查询本月-->
                <!--</button>-->
                <!--<button class="layui-btn" lay-filter="query" lay-submit="" onclick="$('#defaulttime').val('year')">-->
                    <!--<i class="layui-icon layui-icon-search"></i>查询今年-->
                <!--</button>-->
            <!--</div>-->
            <!--<div class="layui-inline">-->
                <!--<input id="defaulttime" class="layui-input" name="defaulttime" placeholder="默认时间" type="hidden">-->
                <!--<div class="layui-inline">-->
                    <!--<input class="layui-input test-item" name="stime" placeholder="开始日" type="text">-->
                <!--</div>-->
                <!--<div class="layui-inline">-->
                    <!--<input class="layui-input test-item" name="ltime" placeholder="截止日" type="text">-->
                <!--</div>-->
            <!--</div>-->
            <!--<div class="layui-inline">-->
                <!--<label class="layui-form-label">商户名称</label>-->
                <!--<div class="layui-input-inline">-->
                    <!--<input type="text" name="business_name" autocomplete="off" placeholder="请输入商户名称"-->
                           <!--class="layui-input">-->
                <!--</div>-->
            <!--</div>-->
            <!--<div class="layui-inline">-->
                <!--<label class="layui-form-label">交易流水号</label>-->
                <!--<div class="layui-input-inline">-->
                    <!--<input type="text" name="e-batch" autocomplete="off" placeholder="请输入交易流水号" class="layui-input">-->
                <!--</div>-->
            <!--</div>-->
            <!--<div class="layui-inline">-->
                <!--<label class="layui-form-label">订单编号</label>-->
                <!--<div class="layui-input-inline">-->
                    <!--<input type="text" name="e-order_id" autocomplete="off" placeholder="请输入交易流水号" class="layui-input">-->
                <!--</div>-->
            <!--</div>-->
            <!--<div class="layui-inline">-->
                <!--<label class="layui-form-label">商户编号</label>-->
                <!--<div class="layui-input-inline">-->
                    <!--<input type="text" name="e-business_id" autocomplete="off" placeholder="请输入商户编号"-->
                           <!--class="layui-input">-->
                <!--</div>-->
            <!--</div>-->
            <!--<div class="layui-inline">-->
                <!--<label class="layui-form-label">通道编号</label>-->
                <!--<div class="layui-input-inline">-->
                    <!--<select name="passageway_id" lay-search="">-->
                        <!--<option value="">选取通道</option>-->
                        <!--<?php if(is_array($passageway_list) || $passageway_list instanceof \think\Collection || $passageway_list instanceof \think\Paginator): $i = 0; $__LIST__ = $passageway_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$passageway): $mod = ($i % 2 );++$i;?>-->
                        <!--<option value="<?php echo $passageway['id']; ?>"><?php echo $passageway['name']; ?></option>-->
                        <!--<?php endforeach; endif; else: echo "" ;endif; ?>-->
                    <!--</select>-->
                <!--</div>-->
            <!--</div>-->
            <!--<div class="layui-inline">-->
                <!--<label class="layui-form-label">金额</label>-->
                <!--<div class="layui-input-inline">-->
                    <!--<input type="text" name="e-amount" autocomplete="off" placeholder="请输入金额" class="layui-input">-->
                <!--</div>-->
            <!--</div>-->
            <!--<div class="layui-inline">-->
                <!--<label class="layui-form-label">状态</label>-->
                <!--<div class="layui-input-inline">-->
                    <!--<select name="e-status">-->
                        <!--<option value="">请选择一个状态</option>-->
                        <!--<option value="0">审核中</option>-->
                        <!--<option value="1">成功</option>-->
                        <!--<option value="2">未提交</option>-->
                        <!--<option value="3">已提交</option>-->
                        <!--<option value="4">代付失败</option>-->
                        <!--<option value="5">提交失败</option>-->
                        <!--<option value="5">已退回</option>-->
                    <!--</select>-->
                <!--</div>-->
            <!--</div>-->
            <!--<button class="layui-btn" lay-filter="query" lay-submit="">-->
                <!--<i class="layui-icon layui-icon-search"></i>查询-->
            <!--</button>-->
            <!--<button class="layui-btn" lay-filter="clear" lay-submit="">-->
                <!--<i class="layui-icon layui-icon-circle"></i>清空条件-->
            <!--</button>-->
        </div>
    </div>
</form>
<table class="layui-hide" id="menu-table" lay-filter="text"></table>
</body>
<script type="text/javascript" src="/static/manage/js/jquery.js"></script>
<script type="text/javascript" src="/static/layui/layui.js"></script>
<script type="text/javascript" src="/static/manage/js/base.js"></script>
<script>
    layui.use(['form', 'laydate', 'table'], function () {
        var form = layui.form,
            laydate = layui.laydate,
            table = layui.table;
        //渲染时间选择器
        lay('.test-item').each(function () {
            laydate.render({
                elem: this,
                trigger: 'click'
            });
        });
        //监听查询提交
        form.on('submit(query)', function (data) {
            table.reload('LogList', {
                url: '<?php echo url("index"); ?>',
                where: data.field
            });
            $('#defaulttime').val('');
            return false;
        });

        form.on('submit(clear)', function () {
            window.refresh();
            return false;
        });

        //表格数据渲染
        table.render({
            elem: '#menu-table',
            method: 'post',
            url: '<?php echo url("index"); ?>',
            limit: 10,
            id: 'LogList',
            limits: [5, 10, 15, 20, 50, 100], //每页条数的选择项
            loading: true,
            where: {order: 'id desc'},
            cellMinWidth: 80, //全局定义常规单元格的最小宽度，layui 2.2.1 新增
            cols: [
                [
                    {field: 'id', title: 'ID'},
                    // {field: 'bank_id', title: '银行id'},
                    {field: 'order_id', title: '订单编号'},
                    {field: 'batch', title: '订单流水号'},
                    {field: 'user_passageway_id', title: '用户通道'},
                    {field: 'business_id', title: '商户名称'},
                    {field: 'amount', title: '金额'},
                    // {field: 'commission', title: '费率'},
                    // {field: 'service_charges', title: '服务费'},
                    {field: 'create_time', title: '创建时间'},
                    {field: 'pay_from', title: '付款人'},
                    // {field: 'pay_info', title: '付款信息'},
                    // {field: 'pay_time', title: '付款时间'},
                    {field: 'status', title: '订单状态'},
                    {field: 'back_time', title: '回调时间'},
                    // {field: 'back_info', title: ' 回调信息'},
                    {field: 'back_status', title: '回调状态'},
                ]
            ],
            page: true,

            response: {
                statusCode: "1" //重新规定成功的状态码为 200，table 组件默认为 0
            },
            parseData: function (res) { //res 即为原始返回的数据
                console.log(res.data.list);
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