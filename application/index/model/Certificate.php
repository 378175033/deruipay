<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/7/18 0018
 * Time: 14:46
 */

namespace app\index\model;


use think\Model;

class Certificate extends Model
{
    /**
     * 2019/7/18 0018 15:19
     * @return bool
     * 生成公匙和私匙
     */
    public function exportOpenSSLFile($business){
        $config = array(
            'config' => 'D:\phpStudy\PHPTutorial\php\php-7.2.1-nts\extras\ssl\openssl.cnf',
            "digest_alg"        => "sha512",
            "private_key_bits"     => 4096,           //字节数  512 1024 2048  4096 等
            "private_key_type"     => OPENSSL_KEYTYPE_RSA,   //加密类型
        );
        $res = openssl_pkey_new($config);
        if ( $res == false ) return false;
        openssl_pkey_export($res, $private_key, null, $config);
        $public_key = openssl_pkey_get_details($res);
        $public_key = $public_key["key"];
        if(!file_exists('certs')){
            mkdir('certs');
        }
        $public_path = 'certs/cert_public_'.$business.'.key';
        $private_path = 'certs/cert_private_'.$business.'.key';
        file_put_contents($public_path, $public_key);
        file_put_contents($private_path, $private_key);
        openssl_free_key($res);
    }

    /**
     * 2019/7/18 0018 15:44
     * @param $string
     * @param string $operation
     * @return string
     * 加密 E/解密 D
     */
    public function authcode($string, $operation = 'E') {
        $ssl_public     = file_get_contents("certs/cert_public.key");
        $ssl_private    = file_get_contents("certs/cert_private.key");
        $pi_key         = openssl_pkey_get_private($ssl_private);//这个函数可用来判断私钥是否是可用的，可用返回资源id Resource id
        $pu_key         = openssl_pkey_get_public($ssl_public);//这个函数可用来判断公钥是否是可用的
        if( false == ($pi_key || $pu_key) ) return '证书错误';
        $data = "";
        if( $operation == 'D') {
            openssl_private_decrypt(base64_decode($string),$data,$pi_key);//私钥解密
        } else {
            openssl_public_encrypt($string, $data, $pu_key);//公钥加密
            $data = base64_encode($data);
        }
        return $data;
    }
}