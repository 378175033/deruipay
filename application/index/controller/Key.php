<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/7/18 0018
 * Time: 14:32
 */

namespace app\index\controller;


use pay\speech\AipSpeech;
use think\Controller;
use phpseclib\Crypt\RSA;
use think\Exception;
use think\Request;

class Key extends Controller
{
    public function create_rsa_key($business_id)
    {
        $rsa = new RSA();
        $res = $rsa->createKey($bits = 1024);
        if ($res['partialkey']){
            $this->error('密钥生成失败！');
        }
        $public = $this->save_key("businessCert/".$business_id."/public.pem", $res['publickey']);
        $private = $this->save_key("businessCert/".$business_id."/private.pem", $res['privatekey']);
        if(!$public || !$private){
            $this->error('生成文件错误！');
        }
    }

    /**
     * 2019/7/19 0019 10:41
     * 生成平台公钥和私钥
     */
    public function create_my_rsa_key()
    {
        $rsa = new RSA();
        $res = $rsa->createKey($bits = 1024);
        if ($res['partialkey']){
            $this->error('密钥生成失败！');
        }
        $public = $this->save_key("myCert/public.pem", $res['publickey']);
        $private = $this->save_key("myCert/private.pem", $res['privatekey']);
        if(!$public || !$private){
            $this->error('生成文件错误！');
        }
    }

    private function save_key($path, $content)
    {
        try{
            $file = fopen($path, "w+");
            fwrite($file, $content);
            fclose($file);
        }catch (Exception $exception){
            return false;
        }
        return true;
    }

    //用商户的公钥加密
    public function encrypt($content, $business_id)
    {
        $rsa = new RSA();
        $path = "businessCert_".$business_id."/public.pem";
        $key = file_get_contents($path);
        if ($rsa->loadKey($key)) {
            return $rsa->encrypt($content);
        }
        return false;
    }

    //用平台的私钥解密
    public function decrypt($content)
    {
        $rsa = new RSA();
//        $path = "businessCert/$business_id"."private.pem";
        $path = "myCert/private.pem";
        $key = file_get_contents($path);
        if ($rsa->loadKey($key)) {
            return $rsa->decrypt($content);
        }
        return false;
    }
}