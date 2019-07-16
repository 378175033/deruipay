<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/7/16 0016
 * Time: 15:53
 */

namespace app\common\controller;

/**
 * Class Sign 签名算法
 * @package app\common\controller
 */
class Sign
{
    private $param;
    private $key = '';

    public function __construct( $param = [] , $key = '')
    {
        if( count( $param ) > 0  ){
            ksort( $param);
            reset( $param);
            $this->param = $param;
            $this->param = $this->build_param();
            return $this->param;
        }
        if( !empty( $key ) ) $this->key = $key;
    }

    /**
     * @desc 拼接数组参数
     * Created by PhpStorm
     * User: zhaolan
     * Date: 2019/7/16 0016 16:17
     * @return string
     */
    private function build_param()
    {
        $str = "";
        if( is_array( $this->param ) && count( $this->param ) ){
            $arr = [];
            foreach ( $this->param as $key => $val )
            {
                //去除数组中的空值
                if( empty( $val ) ) continue;
                else $arr[] = $key."=".$val;
            }
            $str = implode( "&", $arr );
        }
        if(get_magic_quotes_gpc()){
            $str = stripslashes($str);
        }
        return $str;
    }

}