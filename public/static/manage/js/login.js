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
    var form = layui.form;
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