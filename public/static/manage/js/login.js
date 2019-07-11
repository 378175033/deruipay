toastr.options = {
    closeButton: false,  	//是否显示关闭按钮（提示框右上角关闭按钮）。
    debug: false,  			//是否为调试。
    progressBar: true,  	//是否显示进度条（设置关闭的超时时间进度条）
    positionClass: "toast-top-center",  	//消息框在页面显示的位置
    onclick: null,  		//点击消息框自定义事件
    showDuration: "300",  	//显示动作时间
    hideDuration: "1000",  	//隐藏动作时间
    timeOut: "2000",  		//自动关闭超时时间
    extendedTimeOut: "1000",
    showEasing: "swing",
    hideEasing: "linear",
    showMethod: "fadeIn",  	//显示的方式，和jquery相同
    hideMethod: "fadeOut"  	//隐藏的方式，和jquery相同
    //等其他参数
};
layui.use(['form'], function () {
    var form = layui.form,m=60;

    $(document).on('click','.send_sms',function () {
        var that = $(this);
        var username = $('#username').val();
        if( username === ""){
            toastr.error("请填入手机号码！");
            $('#username').focus().addClass('layui-form-danger');
            return false;
        }
        //校验手机号
        if(!(/^1[3456789]\d{9}$/.test(username))){
            toastr.error("手机号有误，请重新输入！");
            $('#username').focus().addClass('layui-form-danger');
            return false;
        }
        that.removeClass("layui-btn-primary").addClass("layui-btn-disabled").attr("disabled", true);
        $.post('/sms.html',{mobile:username,use:'user'},function (res) {
            if( res.code !== 1 ){
                toastr.error( res.msg );
            }
        })
        var intval = setInterval(function () {
            m--;
            if( m < 0 ){
                clearInterval( intval );
                m = 60;
                that.removeClass("layui-btn-disabled").addClass("layui-btn-primary").attr("disabled", false);
                that.html( "获取验证码" );
            } else {
                that.html( "重新获取("+m+")");
            }
        },1000)
    })
    //校验参数
    form.verify({
        username: function(value){ //value：表单的值、item：表单的DOM对象
            if( value === ""){
                toastr.error("请填入手机号码！");
                return "请填入手机号码！";
            }
            //校验手机号
            if(!(/^1[3456789]\d{9}$/.test(value))){
                toastr.error("手机号有误，请重新输入！");
                return "手机号有误，请重新输入！";
            }
        },
        password:function(value){
            if( value === ""){
                toastr.error("请填入登录密码！");
                return "请填入登录密码！";
            }
            //校验密码
            if(!(/^[\S]{6,12}$/.test(value))){
                toastr.error("密码必须6到12位，且不能出现空格");
                return "密码必须6到12位，且不能出现空格";
            }
        }
    });
    //登录按钮事件
    form.on("submit(login)", function ( data ) {
        var code = data.field;
        $.post( 'index',code,function (res) {
            if( res.code === 1 ){
                toastr.success( res.msg ,function () {
                    setTimeout( function () {
                        window.location.href = res.url;
                    },1500)
                })
            } else{
                $('#captcha').trigger('click');
                toastr.error( res.msg )
            }
        })
        return false;
    });
});