<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:81:"D:\phpStudy\PHPTutorial\WWW\F4\public/../application/manage\view\index\index.html";i:1568098091;}*/ ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>支付平台管理系统</title>
    <link rel="stylesheet" href="/static/layui/css/layui.css" id="layui">
    <link rel="stylesheet" href="/static/manage/css/default.css" id="theme">
    <link rel="stylesheet" href="/static/manage/css/kitadmin.css" id="kitadmin">
    <link rel="stylesheet" href="/static/manage/js/toastr/build/toastr.css">
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
                    <a href="#/Index/welcome">支付平台系统</a>
                </div>
            </div>
            <div class="layui-layout-left">
                <div class="kit-search">
                  <form onsubmit="return false;">
                    <input type="text" name="keyword" class="kit-search-input" placeholder="菜单快捷查询" />
                    <button class="kit-search-btn" title="搜索" type="submit">
                      <i class="layui-icon">&#xe615;</i>
                    </button>
                  </form>
                </div>
            </div>
            <div class="layui-layout-right">
                <ul class="kit-nav" lay-filter="header_right">
                    <li class="kit-item">
                        <a href="javascript:" id="clear">
                            <i class="layui-icon">&#xe669;</i>
                            <span>清除缓存</span>
                        </a>
                    </li>
                    <li class="kit-item" kit-target="help">
                        <a href="javascript:">
                            <i class="layui-icon">&#xe607;</i>
                            <span>帮助</span>
                        </a>
                    </li>
                    <li class="kit-item" id="ccleft">
                        <a href="javascript:">
                            <i class="layui-icon">&#xe60e;</i>
                        </a>
                    </li>
                    <!--👉右侧边栏显示
                    <li class="kit-item" id="cc">
                        <a href="javascript:;">
                            <i class="layui-icon">&#xe64c;</i>
                        </a>
                    </li>
                    -->
                    <li class="kit-item">
                        <a href="javascript:">
                  <span>
                      <?php if(empty($user['avatar']) || (($user['avatar'] instanceof \think\Collection || $user['avatar'] instanceof \think\Paginator ) && $user['avatar']->isEmpty())): ?>
                        <img src="http://m.zhengjinfan.cn/images/0.jpg" class="layui-nav-img avatar">
                      <?php else: ?>
                        <img src="/uploads/head/<?php echo $user['avatar']; ?>" alt="" class="layui-nav-img avatar">
                      <?php endif; ?>
                      <?php echo $user['nickname']; ?>
                  </span>
                        </a>
                        <ul class="kit-nav-child kit-nav-right">
                            <li class="kit-item">
                                <a href="#/User/center">
                                    <i class="layui-icon">&#xe612;</i>
                                    <span>个人中心</span>
                                </a>
                            </li>
                            <li class="kit-item" kit-target="setting">
                                <a href="javascript:">
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
        <style>
            .kit-theme-default .layui-layout-admin .layui-side .layui-side-scroll .kit-menu a{
                height: 22px;
                padding: 15px;
                font-size: 16px;
            }
            iframe{
                padding-left: 60px;
                box-sizing: border-box;
            }
           .layui-layout-admin .layui-side {
                width: 260px!important;
            }
            .layui-side-scroll{
                width: 260px!important;
            }
            .layui-side-scroll li{
                width: 260px!important;
            }
            .layui-icon{
                margin-right: 30px;
            }
        </style>
        <!-- silds -->
        <div class="layui-side" kit-side="true">
            <div class="layui-side-scroll">
                <div id="menu-box">
                    <ul class="kit-menu">
                        <?php if(is_array($menu) || $menu instanceof \think\Collection || $menu instanceof \think\Paginator): $i = 0; $__LIST__ = $menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$me): $mod = ($i % 2 );++$i;?>
                            <li class="kit-menu-item">
                                <?php if(empty($me['child']) || (($me['child'] instanceof \think\Collection || $me['child'] instanceof \think\Paginator ) && $me['child']->isEmpty())): ?>
                                <a href="#/<?php echo $me['controller']; ?>/<?php echo $me['action']; ?>">
                                    <i class="layui-icon <?php echo $me['icon']; ?>"></i>
                                    <span><?php echo $me['name']; ?></span>
                                </a>
                                <?php else: ?>
                                <a href="javascript:">
                                    <i class="layui-icon <?php echo $me['icon']; ?>"></i>
                                    <span><?php echo $me['name']; ?></span>
                                </a>
                                <ul class="kit-menu-child layui-anim layui-anim-upbit">
                                    <?php if(is_array($me['child']) || $me['child'] instanceof \think\Collection || $me['child'] instanceof \think\Paginator): $i = 0; $__LIST__ = $me['child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$mc): $mod = ($i % 2 );++$i;?>
                                        <li class="kit-menu-item">
                                            <a href="#/<?php echo $mc['controller']; ?>/<?php echo $mc['action']; ?>">
                                                <i class="layui-icon <?php echo $mc['icon']; ?>"></i>
                                                <span><?php echo $mc['name']; ?></span>
                                            </a>
                                        </li>
                                    <?php endforeach; endif; else: echo "" ;endif; ?>
                                </ul>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
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

    <script type="text/javascript" src="/static/manage/js/jquery.js"></script>
    <script src="/static/layui/polyfill.min.js"></script>
    <script src="/static/layui/layui.js"></script>
    <script src="/static/layui/kitadmin.js"></script>
    <script src="/static/layui/mockjs-config.js"></script>
    <script type="text/javascript" src="/static/manage/js/toastr/toastr.js"></script>
    <script type="text/javascript" src="/static/manage/js/base.js"></script>
    <script>
        layui.use("admin")
    </script>
</body>
</html>