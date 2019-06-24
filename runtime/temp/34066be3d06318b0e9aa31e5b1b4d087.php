<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:83:"D:\phpStudy\PHPTutorial\WWW\F4\public/../application/business\view\index\index.html";i:1561025183;}*/ ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>支付平台商户管理系统</title>
    <link rel="stylesheet" href="/static/layui/css/layui.css" id="layui">
    <link rel="stylesheet" href="/static/manage/css/default.css" id="theme">
    <link rel="stylesheet" href="/static/manage/css/kitadmin.css" id="kitadmin">
    <script type="text/javascript" src="/static/manage/js/jquery.js"></script>
</head>
<body class="layui-layout-body kit-theme-default">
    <div class="layui-layout layui-layout-admin">
        <!-- header -->
        <div class="layui-header">
            <div class="layui-logo">
                <div class="layui-logo-toggle" kit-toggle="side" data-toggle="on">
                    <i class="layui-icon">&#xe65a;</i>
                </div>
                <div class="layui-logo-brand">
                    <a href="#/Index/welcome">支付系统--商户</a>
                </div>
            </div>
            <div class="layui-layout-left">
                <div class="kit-search">
                  <form action="/">
                    <input type="text" name="keyword" class="kit-search-input" placeholder="关键字..." />
                    <button class="kit-search-btn" title="搜索" type="submit">
                      <i class="layui-icon">&#xe615;</i>
                    </button>
                  </form>
                </div>
            </div>
            <div class="layui-layout-right">
                <ul class="kit-nav" lay-filter="header_right">
                    <li class="kit-item">
                        <a href="javascript:;">
                              <span>
                                  <?php if(empty($user['avatar']) || (($user['avatar'] instanceof \think\Collection || $user['avatar'] instanceof \think\Paginator ) && $user['avatar']->isEmpty())): ?>
                                    <img src="http://m.zhengjinfan.cn/images/0.jpg" class="layui-nav-img">
                                  <?php else: ?>
                                    <img src="<?php echo $user['avatar']; ?>" alt="" class="layui-nav-img">
                                  <?php endif; ?>
                                  <?php echo $user['name']; ?>
                              </span>
                        </a>
                        <ul class="kit-nav-child kit-nav-right">
                            <li class="kit-item">
                                <a href="#/index/welcome">
                                    <i class="layui-icon">&#xe612;</i>
                                    <span>个人中心</span>
                                </a>
                            </li>
                            <li class="kit-item" kit-target="setting">
                                <a href="javascript:;">
                                    <i class="layui-icon">&#xe614;</i>
                                    <span>设置</span>
                                </a>
                            </li>
                            <li class="kit-nav-line"></li>
                            <li class="kit-item">
                                <a href="<?php echo url('login/logout'); ?>">
                                    <i class="layui-icon">&#x1006;</i>
                                    <span>注销</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <!-- silds -->
        <div class="layui-side" kit-side="true">
            <div class="layui-side-scroll">
                <div id="menu-box">
                    <ul class="kit-menu">
                        <li class="kit-menu-item">
                            <a href="#/index/welcome">
                                <i class="layui-icon layui-icon-home"></i>
                                <span>商户首页</span>
                            </a>
                        </li>
                        <li class="kit-menu-item">
                            <a href="javascript:;">
                                <i class="layui-icon layui-icon-service"></i>
                                <span>我的订单</span>
                            </a>
                            <ul class="kit-menu-child layui-anim layui-anim-upbit">
                                <li class="kit-menu-item">
                                    <a href="#/order/index">
                                        <i class="layui-icon"></i>
                                        <span>订单列表</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="kit-menu-item">
                            <a href="javascript:;">
                                <i class="layui-icon layui-icon-set"></i>
                                <span>账户配置</span>
                            </a>
                            <ul class="kit-menu-child layui-anim layui-anim-upbit">
                                <li class="kit-menu-item">
                                    <a href="#/user/profile">
                                        <i class="layui-icon"></i>
                                        <span>账户信息</span>
                                    </a>
                                </li>
                                <li class="kit-menu-item">
                                    <a href="#/user/user_passageway_list">
                                        <i class="layui-icon"></i>
                                        <span>通道信息</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="kit-menu-item">
                            <a href="javascript:;">
                                <i class="layui-icon layui-icon-diamond"></i>
                                <span>日志管理</span>
                            </a>
                            <ul class="kit-menu-child layui-anim layui-anim-upbit">
                                <li class="kit-menu-item">
                                    <a href="#/login_log/index">
                                        <i class="layui-icon"></i>
                                        <span>登录日志</span>
                                    </a>
                                </li>
                                <li class="kit-menu-item">
                                    <a href="#/order_log/index">
                                        <i class="layui-icon"></i>
                                        <span>余额变动明细</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- main -->
        <div class="layui-body" kit-body="true">
            <router-view></router-view>
        </div>
        <!-- footer -->
        <div class="layui-footer" kit-footer="true">
            2017 © kit.zhengjinfan.cn MIT license
            <div style="width:400px; height:400px;" class="load-container load1">
                <div class="loader">Loading...</div>
            </div>
        </div>
    </div>
    <script src="/static/layui/polyfill.min.js"></script>
    <script src="/static/layui/layui.js"></script>
    <script src="/static/layui/kitBusiness.js"></script>
    <script src="/static/layui/mockjs-config.js"></script>
    <script>
        layui.use("admin")
    </script>
</body>
</html>