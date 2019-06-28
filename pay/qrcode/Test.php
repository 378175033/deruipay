<?php

namespace pay\qrcode;


class Test{
    public function index( $data )
    {
        ini_set ('memory_limit', '256M');
        if(!isset($_SESSION['think'])){
            echo "error";
            exit();
        }
        header("Content-type:text/html;charset=utf-8");
        if (isset($data['base64'])){
            $b64 = $data['base64'];
        }else{
            $file = file_get_contents($_FILES["file"]["tmp_name"]);
            $b64 = base64_encode($file);
        }
        $qrcode = new QrReader(base64_decode($b64),QrReader::SOURCE_TYPE_BLOB);  //图片路径
        $text = $qrcode->text(); //返回识别后的文本
        return $text;
    }
}

