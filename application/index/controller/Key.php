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

class Key extends Controller
{
    /**
     * 2019/7/19 0019 10:41
     * @param $business_id
     * 生成商户公钥和私钥
     */
    public function create_rsa_key($business_id)
    {
        $rsa = new RSA();
        $res = $rsa->createKey();
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
        $res = $rsa->createKey();
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

    public function encrypt($content)
    {
        $rsa = new RSA();
        return $rsa->encrypt($content);
    }

    public function decrypt($content)
    {
        $rsa = new RSA();
        return $rsa->decrypt($content);
    }
}