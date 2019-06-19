<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/19 0019
 * Time: 10:21
 */

namespace app\manage\controller;
use think\Controller;

class Upload extends Controller
{
    public function upload(){
        // 获取表单上传文件
        $file = request()->file('file');
        $param = request()->param();
        $folder = request()->param("folder", "");
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->validate(['size'=>2048*1024,'ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads'.DS.$folder);
        if($info){
            // 成功上传后 获取上传信息
            // 输出 后缀 如：jpg等
//            echo $info->getExtension();
            // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
            $this->success( $info->getSaveName() );
            // 输出 42a79759f284b767dfcb2a0197904287.jpg
//            echo $info->getFilename();
        }else{
            $this->error( $file->getError() );
        }
    }
}