<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/18 0018
 * Time: 15:31
 *  基础查询方法
 */
namespace app\manage\model;
use think\Model;
use think\Db;
class PublicDb extends Model
{
    public function all_select($table,$where)  // 查询
    {
      return db::table($table)->where($where)->select();
    }

    public function all_add($table,$data) // 新增
    {
        $data = Db::name($table)->insert($data);

        return $data;
    }
    public function pageList($table,$where,$limit) // 分页查询
    {
        $data = db::table($table)->where($where)->page($limit)->select();
        if (empty($data))
        {
            return false;
        }
        else
        {
            return $data;
        }
    }
    public function modify($table,$where,$str) // 更新
    {
        $data = db::name($table)->where($where)->update($str);
        return $data;
    }



}