<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/7/11 0011
 * Time: 9:50
 */

namespace app\common\model;


use app\common\controller\Curl;
use think\Config;
use think\Model;


class Sms extends Model
{
    public $code = [
        100 => '发送成功',
        101 => '验证失败',
        102 => '短信不足',
        103 => '操作失败',
        104 => '非法字符',
        105 => '内容过多',
        106 => '号码过多',
        107 => '频率过快',
        108 => '号码内容空',
        109 => '账号冻结',
        112	=> '号码错误',
        113	=> '定时出错',
        116 => '禁止接口发送',
        117 => '绑定IP不正确',
        161 => '未添加短信模板',
        162 => '模板格式不正确',
        163 => '模板ID不正确',
        164 => '全文模板不匹配',
        166 => '模板内容重复',
        167 => '模板审核中',
        168 => '模板审核不通过 ',
    ];

    public function sendCode($mobile,$content=""){

        $config = config("SMS");

        $uid = $config['UID'];

        $url = $config['URL'];

        $pwd = md5($config['PASSWORD'].$uid);

        $Curl = new Curl();
        $code = random_int(1000,9999);
        $content = $content?$content:"登录验证码：{$code}。如非本人操作，可不用理会！【志成科技】";
        $https = http_build_query([
            'uid'=>$uid,
            'pwd'=>$pwd,
            'mobile'=>$mobile,
            'content'=>$content,
            'template'=>$config['TEMPLATE'],
        ]);
        $url = $url."&".$https;

        $response = $Curl->get($url);
        $response = json_decode($response,true);
        return $response;


    }
}