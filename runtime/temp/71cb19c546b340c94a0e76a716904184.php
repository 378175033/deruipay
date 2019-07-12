<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:78:"D:\phpStudy\PHPTutorial\WWW\F4\public/../application/index\view\pay\index.html";i:1562751970;}*/ ?>
<!doctype html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>支付测试</title>
    <link rel="stylesheet" href="/static/layui/css/layui.css">
    <link rel="stylesheet" href="/static/toastr/build/toastr.css">
    <style>
        .layui-card-body{
            overflow: hidden;
        }
        .mt60{
            margin-top: 60px;
        }
        .top-head{
            border-radius: 15px;
            padding: 0 30px;
        }
        .top-head .layui-card-body{
            line-height: 36px;
            font-size: 16px;
        }
        .th-r{
            line-height: 72px
        }
        .th-r input{
            width: 50%;
            display: inline-block;
            margin: 0 15px;
            padding: 0 30px;
        }
        .cen-body{
            margin: 30px auto;
        }
        .cen-body ul{
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .cen-body ul li{
            width: 16%;
        }
        .cen-body ul li div{
            width: 100%;
            text-align: center;
            line-height: 60px;
            border: 1px solid #aaa;
            cursor: pointer;
        }
        .cen-body ul li div.active{
            border-color: #1a8ae1;
            box-shadow: 1px 1px 1px #1a8ae1;
        }
        .cen-body ul li div img{
            width: 32px;
            margin-right: 10px;
        }
        .price{
            color: #f00;
            font-weight: 700;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="layui-container mt60 layui-form">
        <div class="layui-card top-head">
            <div class="layui-card-body">
                <div class="layui-col-md8">
                    商品名称：<?php echo $order['title']; ?>
                    <br />
                    订单编号：<?php echo $order['order_id']; ?>
                </div>
                <div class="layui-col-md4 th-r">
                    订单金额：<input type="text" name="amount" class="layui-input" value="1.00" step="0.01" onkeyup="format_input_num( this )">元
                </div>
            </div>
        </div>
        <div class="layui-card top-head">
            <div class="layui-card-body">
                <div class="layui-col-md12">
                    支付方式
                </div>
                <div class="layui-col-md12 cen-body">
                    <ul>
                        <?php if(is_array($way) || $way instanceof \think\Collection || $way instanceof \think\Paginator): $i = 0; $__LIST__ = $way;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$w): $mod = ($i % 2 );++$i;?>
                        <li>
                            <div data-id="<?php echo $w['id']; ?>">
                                <img src="/uploads/passageway/<?php echo $w['icon']; ?>" alt=""><?php echo $w['name']; ?>
                            </div>
                        </li>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="layui-card top-head">
            <div class="layui-card-body">
                <div class="layui-col-md12" style="text-align: right">
                    需支付：<span class="price">1.00</span>元
                    <input type="hidden" name="passageway" id="type">
                    <input type="hidden" name="id" value="<?php echo $order['id']; ?>">
                    <button class="layui-btn" lay-submit lay-filter="formDemo" style="margin-left: 20px">立即支付</button>
                </div>
            </div>
        </div>
    </div>
</body>
<script type="text/javascript" src="/static/index/js/jquery.js"></script>
<script type="text/javascript" src="/static/layui/layui.js"></script>
<script type="text/javascript" src="/static/toastr/toastr.js"></script>
<script>
    $('.th-r input').blur(function () {
        var v = $(this).val();
        if( v === "") v = 0;
        $('.price').html( parseFloat(v).toFixed(2) )
    })
    $('.cen-body ul li div').click( function () {
        $('.cen-body ul li div.active').removeClass('active');
        $('#type').val( $(this).data('id') );
        $(this).addClass('active');
    })
    // 格式化限制数字文本框输入，只能数字或者两位小数
    function format_input_num(obj){
        // 清除"数字"和"."以外的字符
        obj.value = obj.value.replace(/[^\d.]/g,"");
        // 验证第一个字符是数字
        obj.value = obj.value.replace(/^\./g,"");
        // 只保留第一个, 清除多余的
        obj.value = obj.value.replace(/\.{2,}/g,".");
        obj.value = obj.value.replace(".","$#$").replace(/\./g,"").replace("$#$",".");
        // 只能输入两个小数
        obj.value = obj.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');
    }
    layui.use(['form'], function() {
        var form = layui.form;
        form.on('submit(formDemo)', function(data) {
            var s = data.field;
            console.log( s )
            $.post( "<?php echo url('pay/index'); ?>", s, function (res) {
                console.log( res );
                if( res.code === 1 ){
                    window.location.href = res.url;
                } else {
                    toastr.error( res.msg );
                }
            })
            return false;
        })
    })
</script>
</html>