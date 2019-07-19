<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/7/18 0018
 * Time: 14:32
 */

namespace app\index\controller;


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
        $public = $this->save_key("businessCert/public_".$business_id.".pem", $res['publickey']);
        $private = $this->save_key("businessCert/private_".$business_id.".pem", $res['privatekey']);
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
        $this->save_key("myCert/public.pem", $res['publickey'])&&
        $this->save_key("myCert/private.pem", $res['privatekey'])&&
        $this->success('success');
        $this->error('生成文件错误！');
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
        $path = "businessCert/$business_id"."public.pem";
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

    public function fun()
    {
        $a = '016CdA8bG3Q0N7/bimVaTImJdFm3i+wlPR/qx2D/TdZTefmX8L/Ck8KIw61NcH2DD/M7fCBvV1Iw6cV2lqqxKHDI3P/0LYjZgHITKFUJhCZQJbTZnRQRMs+T5SoqWVWkmhjpjzawiz0oaehdWPuBTmS0ZgmMQ+VUO/BxhFPtx+exAAXlnWMWZxrBFWiyzugMCTErIVtpd32anRGx6FtZrw==';
        $encodingaeskey  = base64_encode('1234567891011121');
        halt(decode($a), $encodingaeskey);
        dump(encode($this->encrypt('{asd:asd,dsa:dsa}',1), $encodingaeskey));
    }
}