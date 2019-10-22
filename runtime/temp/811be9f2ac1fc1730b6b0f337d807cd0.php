<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:80:"D:\phpStudy\PHPTutorial\WWW\F4\public/../application/index\view\index\index.html";i:1563351234;s:70:"D:\phpStudy\PHPTutorial\WWW\F4\application\index\view\public\head.html";i:1563242933;}*/ ?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <title>首页</title>
    <link rel="stylesheet" type="text/css" href="/static/index/static/swiper.min.css">
    <link rel="stylesheet" type="text/css" href="/static/index/static/index.css">
    <script src="https://unpkg.com/vue/dist/vue.js"></script>
    <link rel="stylesheet" type="text/css" href="/static/index/static/public.css">
<script>
    function resizeFontSize(){
        document.documentElement.style.fontSize = document.documentElement.clientWidth / 960*100 + 'px';
    }
    resizeFontSize();
    window.onresize=function () {
        resizeFontSize();
    }
</script>
</head>
<body>
<div id="login">
    <div id="swiper-banner" class="swiper-container">
        <div class="swiper-wrapper">
            <div class="swiper-slide" style='background-image:url("/static/index/assets/index_banner_1.png");'></div>
            <div class="swiper-slide" style='background-image:url("/static/index/assets/index_banner_2.png");'></div>
            <div class="swiper-slide" style='background-image:url("/static/index/assets/index_banner_3.png");'></div>
        </div>
    </div>
    <div id="header" v-cloak>
        <header-component post-class="publicHeader"></header-component>
    </div>
    <!--<div class="login-now">立即接入</div>-->
    <div class="login-container">
        <p class="login-user" data-value="0" v-model="model.name">商户登录</p>
        <div class="login-input">
            <img class="login-icon" src="/static/index/assets/index_account.png" />
            <input class="login-account" v-model="model.mobile" placeholder="请输入手机号" />
        </div>
        <div class="login-input pwd" src="/static/index/assets/index_psw.png">
            <img class="login-icon" src="/static/index/assets/index_psw.png" />
            <input type="password" class="login-account" v-model="model.password" placeholder="请输入账号密码" />
        </div>
        <div class="login-input again" src="/static/index/assets/index_psw.png" style="display: none">
            <img class="login-icon" src="/static/index/assets/index_psw.png" />
            <input type="password" class="login-account" v-model="model.passwords" placeholder="请再次输入账号密码"/>
        </div>
        <div class="login-input">
            <input class="login-msg" v-model="model.code" placeholder="请输入验证码" />
            <button class="login-send" value="0" @click="sendSms()">{{content}}</button>
            <!--<button class="login-send" value="1" @click="sendSms()" v-if="isDisabled">{{content}}</button>-->
        </div>
        <button class="login-in" @click="login()">确定</button>
        <div class="retrieve" style="display: none">
            <button class="login-retrieve" @click="login()">找回密码</button>
            <button class="login-cancel" @click="cancel()">取消</button>
        </div>
        <div class="login-fun"></div>
        <div class="login-fun reg-login" style="display: none">
            <a href="#" class="login-forget" style="width: 100%;text-align: center"> 已有账号?
                <span class="login" @click="logins()">立即登录</span>
            </a>
        </div>
        <div class="login-fun login-reg">
            <a class="login-remember">
                <input type="checkbox" name="like" value="" checked class="checkbox">
                记住密码
            </a>
            <a href="#" @click="retrievePwd()"class="login-forget">忘记密码？</a>
        </div>
        <div class="login-fun login-reg">
            <a href="#" class="login-regist" style="width: 100%;text-align: center">还没有账号？
                <span class="register" @click="register()">立即注册</span>
            </a>
        </div>
    </div>
</div>
<div id="choose">
    <p class="desc-title">为什么选择我们</p>
    <p class="desc-text"><span>因为专注，所以专业</span></p>
    <div class="choose-container">
        <div class="choose-desc">
            <div>
                <img class="choose-icon" src="/static/index/assets/index_join.png" />
                <div class="choose-text">
                    <h1>接入便利</h1>
                    <p>全平台SKD让你最小化</p>
                    <p>介入支付的时间与人力</p>
                </div>
            </div>
            <div>
                <div>
                    <img class="choose-icon" src="/static/index/assets/index_stable.png" />
                    <div class="choose-text">
                        <h1>稳定可靠</h1>
                        <p>所有数据的传输和存储</p>
                        <p>符合金融级别的安全标准</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="choose-desc">
            <div>
                <img class="choose-icon" src="/static/index/assets/index_sweet.png" />
                <div class="choose-text">
                    <h1>高效贴心</h1>
                    <p>7×24小时在线服务</p>
                    <p>提供快速解决方案</p>
                </div>
            </div>
            <div>
                <img class="choose-icon" src="/static/index/assets/index_safe.png" />
                <div class="choose-text">
                    <h1>安全保证</h1>
                    <p>金融级安全系统、</p>
                    <p>智能监控系统，双重加密</p>
                    <p>确保服务快速稳定交易</p>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="pay">
    <p class="desc-title">支付产品</p>
    <p class="desc-text"><span>多种支付产品任你选择，点击马上体验</span></p>
    <div id="pay-bank">
        <div class="bank-left" onclick="slidePrev()"></div>
        <div id="swiper-pay-type" class="swiper-container">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div class="pay-product">
                        <img src="/static/index/assets/index_pay.png" />
                        <h1>快捷支付</h1>
                        <p>手机端/电脑端（含PC扫一 扫）的多渠道支付</p>
                    </div>
                    <div class="pay-product">
                        <img src="/static/index/assets/index_qrcode.png" />
                        <h1>码支付</h1>
                        <p>2秒支付、60秒条码自动更新</p>
                    </div>
                    <div class="pay-product">
                        <img src="/static/index/assets/index_gateway.png" />
                        <h1>网关支付</h1>
                        <p>使用银行U盾登录网上银行验证支付</p>
                    </div>
                    <div class="pay-product">
                        <img src="/static/index/assets/index_replace.png" />
                        <h1>代付款</h1>
                        <p>单笔或者批量付款至指定银行账户</p>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="pay-product">
                        <img src="/static/index/assets/index_over.png" />
                        <h1>跨境支付</h1>
                        <p>提供收单、外币结算、报送海关等服务</p>
                    </div>
                    <div class="pay-product">
                        <img src="/static/index/assets/index_replace_2.png" />
                        <h1>代扣款</h1>
                        <p>一次签约可多次免密扣款支付</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="bank-right" onclick="sildeNext()"></div>
    </div>
</div>
<div id="cooperation">
    <p>我们的合作伙伴</p>
    <p>Our partners</p>
    <div id="bm"></div>
</div>
<div id="new">
    <div>
        <h1>立即开启支付新时代！</h1>
        <p>得瑞支付，支付技术服务商，让支付简单，专业、快捷，温暖！</p>

        <a href="index.html" class="new-now">立即开启</a>
        <div class="new-radius"></div>
    </div>
</div>
<div id="footer" v-cloak>
    <footer-component></footer-component>
</div>
</body>
<!------公共库------>
<script src="/static/index/public/vue.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.5.1/lottie_html.js"></script>
<script src="/static/index/public/swiper.min.js"></script>
<!------------>
<script src="/static/index/js/public.js"></script>
<script src="/static/index/js/index.js"></script>
<script src="/static/index/js/jquery.js"></script>
<script src="/static/layui/layui.js"></script>
<script>
    layui.use(['layer'], function(){
        var layer = layui.layer;
        var vm = new Vue({
            el:"#login",
            data () {
                return {
                    isDisabled: false, //控制按钮是否可以点击（false:可以点击，true:不可点击）
                    content: '验证码', // 发送验证码按钮的初始显示文字
                    timer: null,
                    count: '',
                    model: {},
                }
            },
            methods: {
                register(){
                    $('.again').show();
                    $('.login-user').text('商户注册');
                    $('.login-user').attr('data-value',1);
                    $('.login-reg').hide();
                    $('.reg-login').show();
                },
                logins(){
                    $('.again').hide();
                    $('.login-user').text('商户登录');
                    $('.login-user').attr('data-value',0);
                    $('.login-reg').show();
                    $('.reg-login').hide();
                },
                retrievePwd(){
                    $('.login-in').hide();
                    $('.reg-login').hide();
                    $('.login-reg').hide();
                    $('.retrieve').show();
                    $('.login-user').text('找回密码');
                    $('.login-user').attr('data-value',2);
                },
                cancel(){
                    $('.again').hide();
                    $('.login-user').text('商户登录');
                    $('.login-user').attr('data-value',0);
                    $('.login-reg').show();
                    $('.reg-login').hide();
                    $('.retrieve').hide();
                    $('.login-in').show();
                },
                sendSms(){
                    if($('.login-send').attr('isDisabled') == "isDisabled"){

                        return;
                    }
                    let vm = this;
                    if(!vm.model.mobile || vm.model.mobile === ''){
                        layer.msg('请输入手机号！');
                        return;
                    }
                    if (!(/^1[3456789]\d{9}$/.test(vm.model.mobile))){
                        layer.msg('请输入正确的手机号');
                        return;
                    }
                    // 控制倒计时及按钮是否可以点击
                    const TIME_COUNT = 60;
                    vm.count = TIME_COUNT;

                    vm.timer = setInterval(() => {
                        if (vm.count > 0 && vm.count <= TIME_COUNT) {
                            // 倒计时时不可点击
                            $('.login-send').attr('isDisabled','isDisabled');
                            vm.isDisabled = true;
                            // 计时秒数
                            vm.count--;
                            // 更新按钮的文字内容
                            vm.content = vm.count + 's';
                        }else {
                            $('.login-send').attr('isDisabled','');
                            // 倒计时完，可点击
                            vm.isDisabled = false;
                            // 更新按钮文字内容
                            vm.content = '验证码';
                            // 清空定时器!!!
                            clearInterval(vm.timer);
                            vm.timer = null
                        }
                    }, 1000);
                    $.post('/sms.html',{mobile:vm.model.mobile},function (res) {
                        vm.isDisabled = false;
                        if( res.code !== 1 ){
                            layer.msg( res.msg );
                        }
                    })
                },
                login(){
                    let lg = this.model;
                    if(!lg.mobile || lg.mobile === ''){
                        layer.msg('请输入手机号！');
                        return;
                    }
                    if (!(/^1[3456789]\d{9}$/.test(lg.mobile))){
                        layer.msg('请输入正确的手机号');
                        return;
                    }

                    if(!lg.code){
                        layer.msg('验证码不能为空！');
                        return;
                    }
                    if(!lg.password){
                        layer.msg('密码不能为空！');
                        return;
                    }
                    var value = $(".login-user").attr('data-value');

                    if(value === '1'){

                        if(lg.password !== lg.passwords){
                            layer.msg('两次密码不一样，请重新输入');
                            return;
                        }
                        this.registerPost(lg);//注册
                    }else if(value === '2'){
                        this.retrievePut(lg);//忘记密码
                    }else{
                        this.loginPost(lg);//登录
                    }
                },
                loginPost(lg){
                    var data = {
                        'mobile':lg.mobile,
                        'code':lg.code,
                        'password':lg.password,
                    };
                    $.post('<?php echo url("index/login"); ?>',data,function (res) {
                        console.log(res);
                        if(res.code == 1){
                            layer.msg( "登录成功！",{icon:1});
                            setTimeout(function () {
                                window.location.href = res.url;
                            },1200)
                        }else{
                            layer.msg(res.msg,{icon:2});
                        }
                    })
                },
                registerPost(lg){
                    var data = {
                        'mobile':lg.mobile,
                        'code':lg.code,
                        'password':lg.password,
                    };
                    $.post('<?php echo url("index/register"); ?>',data,function (res) {
                        console.log(res);
                        if(res.code == 1){
                            layer.msg( "注册成功,请等待审核",{icon:1});
                            setTimeout(function () {
                                window.location.href = res.url;
                            },1200)
                        }else{
                            layer.msg(res.msg,{icon:2});
                        }
                    })
                },
                retrievePut(lg){
                    var data = {
                        'mobile':lg.mobile,
                        'code':lg.code,
                        'password':lg.password,
                    };
                    $.post('<?php echo url("index/retrieve"); ?>',data,function (res) {
                        console.log(res);
                        if(res.code == 1){
                            layer.msg( "忘记密码成功！",{icon:1});
                            setTimeout(function () {
                                window.location.href = res.url;
                            },1200)
                        }else{
                            layer.msg(res.msg,{icon:2});
                        }
                    })
                }
            },
        });
    });
</script>
</html>