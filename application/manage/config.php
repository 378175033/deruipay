<?php
return [
    'view_replace_str' => [
        '__CSS__' => '/static/manage/css',
        '__JS__' => '/static/manage/js',
        '__IMAGE__' => '/static/manage/images',
        '__EDITOR__' => '/static/ueditor',
        '__LAYUI__' => '/static/layui',
    ],

    'pay_type' => [
        1 => '支付宝',
        2 => '微信',
        3 => '通联'
    ],

    'union_pay' => ['test', [
        'version' => '5.1.0',
        'signMethod' => '01', //RSA
        'encoding' => 'UTF-8',
        'merId' => '700000000000001',
        'currencyCode' => 156,
        'returnUrl' => 'http://dev.git.com/union-pay/demo/payreturn.php', //前台网关支付返回
        'notifyUrl' => 'http://dev.git.com/union-pay/demo/paynotify.php', //后台通知
        'frontFailUrl' => 'http://dev.git.com/union-pay/demo/payfail.php',
        'refundnotifyUrl' => 'http://dev.git.com.com/union-pay/demo/refundnotify.php',
        'signCertPath' =>  './cert/acp_test_sign.pfx',
        'signCertPwd' => '000000', //签名证书密码
        'verifyCertPath' =>  './cert/acp_test_verify_sign.cer',  //v5.0.0 required
        'verifyRootCertPath' =>  './cert/acp_test_root.cer', //v5.1.0 required
        'verifyMiddleCertPath' =>  './cert/acp_test_middle.cer', //v5.1.0 required
        'encryptCertPath' =>  './cert/acp_test_enc.cer',
        'ifValidateCNName' => false, //正式环境设置为true
    ]]
];