<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    // +----------------------------------------------------------------------
    // | 应用设置
    // +----------------------------------------------------------------------

    // 应用调试模式
    'app_debug'              => true,
    // 应用Trace
    'app_trace'              => false,
    // 应用模式状态
    'app_status'             => '',
    // 是否支持多模块
    'app_multi_module'       => true,
    // 入口自动绑定模块
    'auto_bind_module'       => false,
    // 注册的根命名空间
    'root_namespace'         => [],
    // 扩展函数文件
    'extra_file_list'        => [THINK_PATH . 'helper' . EXT],
    // 默认输出类型
    'default_return_type'    => 'html',
    // 默认AJAX 数据返回格式,可选json xml ...
    'default_ajax_return'    => 'json',
    // 默认JSONP格式返回的处理方法
    'default_jsonp_handler'  => 'jsonpReturn',
    // 默认JSONP处理方法
    'var_jsonp_handler'      => 'callback',
    // 默认时区
    'default_timezone'       => 'PRC',
    // 是否开启多语言
    'lang_switch_on'         => false,
    // 默认全局过滤方法 用逗号分隔多个
    'default_filter'         => '',
    // 默认语言
    'default_lang'           => 'zh-cn',
    // 应用类库后缀
    'class_suffix'           => false,
    // 控制器类后缀
    'controller_suffix'      => false,

    // +----------------------------------------------------------------------
    // | 模块设置
    // +----------------------------------------------------------------------

    // 默认模块名
    'default_module'         => 'index',
    // 禁止访问模块
    'deny_module_list'       => ['common'],
    // 默认控制器名
    'default_controller'     => 'Index',
    // 默认操作名
    'default_action'         => 'index',
    // 默认验证器
    'default_validate'       => '',
    // 默认的空控制器名
    'empty_controller'       => 'Error',
    // 操作方法后缀
    'action_suffix'          => '',
    // 自动搜索控制器
    'controller_auto_search' => false,

    // +----------------------------------------------------------------------
    // | URL设置
    // +----------------------------------------------------------------------

    // PATHINFO变量名 用于兼容模式
    'var_pathinfo'           => 's',
    // 兼容PATH_INFO获取
    'pathinfo_fetch'         => ['ORIG_PATH_INFO', 'REDIRECT_PATH_INFO', 'REDIRECT_URL'],
    // pathinfo分隔符
    'pathinfo_depr'          => '/',
    // URL伪静态后缀
    'url_html_suffix'        => 'html',
    // URL普通方式参数 用于自动生成
    'url_common_param'       => false,
    // URL参数方式 0 按名称成对解析 1 按顺序解析
    'url_param_type'         => 0,
    // 是否开启路由
    'url_route_on'           => true,
    // 路由使用完整匹配
    'route_complete_match'   => true,
    // 路由配置文件（支持配置多个）
    'route_config_file'      => ['route'],
    // 是否开启路由解析缓存
    'route_check_cache'      => false,
    // 是否强制使用路由
    'url_route_must'         => false,
    // 域名部署
    'url_domain_deploy'      => false,
    // 域名根，如thinkphp.cn
    'url_domain_root'        => '',
    // 是否自动转换URL中的控制器和操作名
    'url_convert'            => true,
    // 默认的访问控制器层
    'url_controller_layer'   => 'controller',
    // 表单请求类型伪装变量
    'var_method'             => '_method',
    // 表单ajax伪装变量
    'var_ajax'               => '_ajax',
    // 表单pjax伪装变量
    'var_pjax'               => '_pjax',
    // 是否开启请求缓存 true自动缓存 支持设置请求缓存规则
    'request_cache'          => false,
    // 请求缓存有效期
    'request_cache_expire'   => null,
    // 全局请求缓存排除规则
    'request_cache_except'   => [],

    // +----------------------------------------------------------------------
    // | 模板设置
    // +----------------------------------------------------------------------

    'template'               => [
        // 模板引擎类型 支持 php think 支持扩展
        'type'         => 'Think',
        // 默认模板渲染规则 1 解析为小写+下划线 2 全部转换小写
        'auto_rule'    => 1,
        // 模板路径
        'view_path'    => '',
        // 模板后缀
        'view_suffix'  => 'html',
        // 模板文件名分隔符
        'view_depr'    => DS,
        // 模板引擎普通标签开始标记
        'tpl_begin'    => '{',
        // 模板引擎普通标签结束标记
        'tpl_end'      => '}',
        // 标签库标签开始标记
        'taglib_begin' => '{',
        // 标签库标签结束标记
        'taglib_end'   => '}',
    ],

    // 视图输出字符串内容替换
    'view_replace_str'       => [],
    // 默认跳转页面对应的模板文件
    'dispatch_success_tmpl'  => THINK_PATH . 'tpl' . DS . 'dispatch_jump.tpl',
    'dispatch_error_tmpl'    => THINK_PATH . 'tpl' . DS . 'dispatch_jump.tpl',

    // +----------------------------------------------------------------------
    // | 异常及错误设置
    // +----------------------------------------------------------------------

    // 异常页面的模板文件
    'exception_tmpl'         => THINK_PATH . 'tpl' . DS . 'think_exception.tpl',

    // 错误显示信息,非调试模式有效
    'error_message'          => '页面错误！请稍后再试～',
    // 显示错误信息
    'show_error_msg'         => false,
    // 异常处理handle类 留空使用 \think\exception\Handle
    'exception_handle'       => '',

    // +----------------------------------------------------------------------
    // | 日志设置
    // +----------------------------------------------------------------------

    'log'                    => [
        // 日志记录方式，内置 file socket 支持扩展
        'type'  => 'File',
        // 日志保存目录
        'path'  => LOG_PATH,
        // 日志记录级别
        'level' => [],
    ],

    // +----------------------------------------------------------------------
    // | Trace设置 开启 app_trace 后 有效
    // +----------------------------------------------------------------------
    'trace'                  => [
        // 内置Html Console 支持扩展
        'type' => 'Html',
    ],

    // +----------------------------------------------------------------------
    // | 缓存设置
    // +----------------------------------------------------------------------

    'cache'                  => [
        // 驱动方式
        'type'   => 'File',
        // 缓存保存目录
        'path'   => CACHE_PATH,
        // 缓存前缀
        'prefix' => '',
        // 缓存有效期 0表示永久缓存
        'expire' => 0,
    ],

    // +----------------------------------------------------------------------
    // | 会话设置
    // +----------------------------------------------------------------------

    'session'                => [
        'id'             => '',
        // SESSION_ID的提交变量,解决flash上传跨域
        'var_session_id' => '',
        // SESSION 前缀
        'prefix'         => 'think',
        // 驱动方式 支持redis memcache memcached
        'type'           => '',
        // 是否自动开启 SESSION
        'auto_start'     => true,
    ],

    // +----------------------------------------------------------------------
    // | Cookie设置
    // +----------------------------------------------------------------------
    'cookie'                 => [
        // cookie 名称前缀
        'prefix'    => '',
        // cookie 保存时间
        'expire'    => 0,
        // cookie 保存路径
        'path'      => '/',
        // cookie 有效域名
        'domain'    => '',
        //  cookie 启用安全传输
        'secure'    => false,
        // httponly设置
        'httponly'  => '',
        // 是否使用 setcookie
        'setcookie' => true,
    ],

    //分页配置
    'paginate'               => [
        'type'      => 'bootstrap',
        'var_page'  => 'page',
        'list_rows' => 15,
    ],

    'union_pay' => [
        'test',
        [
            'version' => '5.1.0',
            'signMethod' => '01', //RSA
            'encoding' => 'UTF-8',
            'merId' => '777290058170687',
            'currencyCode' => 156,
            'returnUrl' => 'http://dev.git.com/union-pay/demo/payreturn.php', //前台网关支付返回
            'notifyUrl' => 'http://dev.git.com/union-pay/demo/paynotify.php', //后台通知
            'frontFailUrl' => 'http://dev.git.com/union-pay/demo/payfail.php',
            'refundnotifyUrl' => 'http://dev.git.com.com/union-pay/demo/refundnotify.php',
            'openReturnUrl' => 'http://dev.git.com/union-pay/demo/payreturn.php',// 前台开通并支付返回地址
            'openNotifyUrl' => 'http://dev.git.com/union-pay/demo/payreturn.php',// 后台开通并支付返回地址
            'signCertPath' =>  './cert/acp_test_sign.pfx',
            'signCertPwd' => '000000', //签名证书密码
            'verifyCertPath' =>  './cert/acp_test_verify_sign.cer',  //v5.0.0 required
            'verifyRootCertPath' =>  './cert/acp_test_root.cer', //v5.1.0 required
            'verifyMiddleCertPath' =>  './cert/acp_test_middle.cer', //v5.1.0 required
            'encryptCertPath' =>  './cert/acp_test_enc.cer',
            'ifValidateCNName' => false, //正式环境设置为true
        ]
    ],

    'daxiangpay' => [
//商户号
        'CC_PAY_MERID' => '18086',
//通道类型
        'CC_PAY_PAY_TYPE_KEY' => 'fast',
//API密钥
        'CC_PAY_API_KEY' => '2bfzed5L8Q39SzNmiMQYYvRNdb0kvG',
//网关地址
        'CC_PAY_POST_URL' => 'http://ds.desheng520.com/api/pay/',

        'callbackurl' => 'http://' . $_SERVER['HTTP_HOST'] . '/manage/daxiangpay/callbacknotify ',

        's2surl' => 'http://' . $_SERVER['HTTP_HOST'] . '/manage/daxiangpay/notify',
//支持的银行列表（不同的通道，支持的银行不同，请在商户中心查询所支持的银行）
        'PAY_BANK_LIST' => array('上海农商行','上海银行','东亚银行','中信银行','中国银行','交通银行','光大银行','兰州银行','兴业银行','农业银行','北京农商行','北京银行','华夏银行','南京银行','天津银行','宁波银行','平安银行','广发银行','广州银行','建设银行','恒丰银行','成都银行','招商银行','杭州银行','民生银行','浙商银行','浦发银行','深发展','渤海银行','邮政储蓄','青岛银行')
    ],
    'SMS' => [
        'URL' => "http://api.sms.cn/sms/?ac=send",
        'UID' => "medea7788",
        'PASSWORD' => "mxkj123",
        'TEMPLATE' => "515029",
    ],


    'tlt' => [
        'certFile' => './data/allinpay-pds.pem',//通联公钥证书
        'privateKeyFile' => './data/20022100001079504.pem',//商户私钥证书
        'userName' => '20022100001079504',
        'merchantId' => '200221000010795',
        'password' => '111111',//商户私钥密码以及用户密码
//        'apiUrl' => 'http://113.108.182.3:8083/aipg/ProcessServlet',//通联系统对接请求地址（外网,商户测试时使用）
        'apiUrl' => 'https://tlt.allinpay.com/aipg/ProcessServlet',//（生产环境地址，上线时打开该注释）
    ]
];
