<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:77:"D:\phpStudy\PHPTutorial\WWW\F4\public/../application/business\view\login.html";i:1560850223;}*/ ?>
<!doctype html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>支付管理系统</title>
    <link rel="stylesheet" href="/static/manage/css/base.css">
    <link rel="stylesheet" href="/static/manage/css/login.css">
    <link rel="stylesheet" href="/static/manage/js/toastr/build/toastr.css">
</head>
<body>
    <div class="main-page login-page ">
        <h2 class="title1">Business Login</h2>
        <div class="widget-shadow">
            <div class="login-body">
                <form style="position: relative" class="layui-form">
                    <input type="text" class="user" name="username" placeholder="登录名" required="">
                    <input type="password" name="password" class="lock" placeholder="密码" required="">
                    <input type="text" name="captcha" class="captcha" placeholder="验证码" required="">
                    <div class="cap_image"><img src="<?php echo url('Login/entry'); ?>" id="captcha" alt="captcha" onclick="this.src='<?php echo url('Login/entry'); ?>'"/></div>
                    <input type="hidden" name="__token__" value="<?php echo \think\Request::instance()->token(); ?>" />
                    <input type="submit" name="Sign In" value="Sign In" lay-submit="" lay-filter="login">
                </form>
            </div>
        </div>
    </div>
</body>
<script type="text/javascript" src="/static/manage/js/jquery.js"></script>
<script type="text/javascript" src="/static/layui/layui.js"></script>
<script type="text/javascript" src="/static/manage/js/toastr/toastr.js"></script>
<script type="text/javascript" src="/static/manage/js/login.js"></script>
</html>