
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

$('#clear').click( function () {
    $.post( "manage/Api/clear",{},function () {
        toastr.success( "成功清除缓存！");
    })
});

function openIframe( obj )
{
    var opt = {
        title     : '数据信息',
        area     : ['60%','80%'],
        content   : "add",
        type       : 2,
        closeBtn    : 2,
        shade   : 0.3,  //你想定义别的颜色，可以shade: [0.8, '#393D49']；如果你不想显示遮罩，可以shade: 0
        shadeClose : false, //是否点击遮罩关闭
    };
    var newObj = $.extend( opt, obj);
    layui.use( 'layer', function () {
        layer = layui.layer;
        layer.open( newObj )
    })
}

/**
 * 编辑页面
 */
$(document).on('click', '.btn-update',function () {
    var url = $(this).data('url') ? $(this).data('url') : 'edit';
    var obj = {
        content : url + '?id='+$(this).data('id'),
        title   : '更新数据信息'
    };
    openIframe( obj );
});

/**
 * 图片预览
 * @param file
 * @returns {*}
 */
function getObjectURL(file) {
    var url = null;
    if (window.createObjectURL != undefined) {
        url = window.createObjectURL(file);
    } else if (window.URL != undefined) {
        url = window.URL.createObjectURL(file);
    } else if (window.webkitURL != undefined) {
        url = window.webkitURL.createObjectURL(file);
    }
    return url;
}
/**
 * 切换状态
 */
$(document).on('click','.status-toggle',function () {
    var id = parseInt( $(this).data('id') );
    var value = parseInt ( $(this).data('value') );
    var that = $(this);
    var notice = $(this).data('notice') ? $(this).data('notice') : "隐藏|显示";
    var url = $(this).data('url') ? $(this).data('url') : 'changeStatus';
    notice = notice.split("|");
    $.post(url,{id:id,value:value},function ( res ) {
        if( res.code ===  1 ){
            toastr.success( res.msg ,function () {
                if( value === 1 ){
                    that.removeClass('layui-btn-danger').addClass('layui-btn-normal');
                    that.html(notice[1]);
                    that.data('value', 0)
                } else {
                    that.removeClass('layui-btn-normal').addClass('layui-btn-danger');
                    that.html(notice[0]);
                    that.data('value', 1)
                }
            })
        } else {
            toastr.error( res.msg )
        }
    })
});
/**
 * 更新排序
 */
$(document).on('blur','.sort-order',function () {
    var id = $(this).data( 'id' );
    var url = $(this).data('url') ? $(this).data('url') : 'sortOrder';
    $.post( url,{value:$(this).val(),id:id},function ( res ) {
        if( res.code ===  1 ){
            toastr.success( res.msg );
        } else {
            toastr.error( res.msg );
        }
    })
});
/**
 * 软删除数据
 */
$(document).on('click','.btn-remove',function () {
    var id = parseInt( $(this).data('id') );
    var ptr = $(this).parents('tr');
    var url = $(this).data('url') ? $(this).data('url') : 'remove';
    $.post( url,{id:id},function (res) {
        if( res.code === 1 ){
            toastr.success( res.msg, function () {
                ptr.remove();
            });
        } else {
            toastr.error( res.msg );
        }
    })
});
/**
 * 真实删除数据
 */
$(document).on('click','.btn-delete',function () {
    var id = parseInt( $(this).data('id') );
    var ptr = $(this).parents('tr');
    var url = $(this).data('url') ? $(this).data('url') : 'delete';
    layer.confirm("您确定要删除吗？无法找回哟！",function () {
        $.post(url,{id:id},function (res) {
            if( res.code === 1 ){
                layer.msg( res.msg, {icon:1});
                ptr.remove();
            } else {
                layer.msg( res.msg, {icon:2});
            }
        })
    })
});