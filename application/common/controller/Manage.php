<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/14 0014
 * Time: 13:16
 */

namespace app\common\controller;
use think\Controller;
/**
 * Class Manage
 * @desc 后端公共文件
 * @package app\common\controller
 */
class Manage extends Controller
{
    /**
     * @var resource 模型变量
     */
    protected $model;
    protected $_limit = '';
    /**
     * @var bool 是否开启数据验证
     */
    protected $is_validate = false;
    /**
     * @var string 数据表名
     */
    protected $table;
    /**
     * @var string 定义默认排序
     */
    protected $order = 'id desc';
    /**
     * @var string 定义默认字段
     */
    protected $field = '*';
    /**
     * @var bool 是否进行验证
     */
    protected $isValidate = true;
    /**
     * @var string 登录用户信息
     */
    protected $userInfo;

    /**
     * @var array 是否关联查询
     */
    protected $join = [];
    /**
     * @var array 定义允许访问的目录
     */
    protected $allow_auth = [];

    protected $admin_id;
    public function _initialize()
    {
        parent::_initialize();
        $this->checkLogin();
        $this->checkAuth();
        $this->admin_id = '管理员id:'.session('userInfo')->id.'>>';
    }

    public function checkLogin()
    {
        if( !session( "?userInfo") ){
            $this->redirect( url("Login/index") );
        }
        $this->userInfo = model( 'user')->where('id', session('userInfo')['id'])->find();
        $this->assign( "user", $this->userInfo);
    }

    public function checkAuth()
    {
        //如果为超级管理员
        if( $this->userInfo['id'] == 1 ){
            return true;
        }

        $controller = request()->controller();
        $action = request()->action();
        $str = $controller."/".$action;
        $allow = [
            'Index/index','Index/welcome'
        ];

        $this->allow_auth = array_merge( $allow, $this->allow_auth );
        if( in_array( $str, $this->allow_auth) ){
            return true;
        }
        //如果是是下划线就下划线转驼峰
        if(count(explode('-',$controller))>0){
            $controller = toUnderScore($controller);
        }

        $where = [
            'controller'    => $controller,
            'action'        => $action,
            'delete_time'   => 0
        ];
        $id = model( 'menu')->where( $where )->value( 'id');
        if( $id ){
            $auth = explode( ",", $this->userInfo['rule'] );
            if( !in_array( $id, $auth) ){
                $this->error( "您没有该权限哟！");
            }
        }else {
            $this->error( "请前往添加菜单".$str);
        }
    }
    /**
     * @return mixed
     * 首页
     */
    public function index()
    {
        if ($this->request->isPost() && $this->request->isAjax()) {
            $page = $this->request->param('page', 1, 'intval');
            $per = $this->request->param('limit', 10, 'intval');
            $this->order = $this->request->param('order', $this->order);
            $where = [
                'a.delete_time' => 0,
            ];
            $param = $this->request->param();
            foreach ($param as $key => $val) {
                $ps = substr($key, 0, 2);
                $vl = substr($key, 2);
                switch ($ps) {
                    case 'l-':
                        $st = substr($vl, 0, 2);
                        if( $st == "b-"){
                            $vl = substr($vl, 2);
                            if (!empty($val)) $where["b.".$vl] = ['like', '%' . $val . '%'];
                        } else {
                            if (!empty($val)) $where["a.".$vl] = ['like', '%' . $val . '%'];
                        }
                        break;
                    case 'e-':
                        $st = substr($vl, 0, 2);
                        if( $st == "b-"){
                            $vl = substr($vl, 2);
                            if ( $val != "")  $where["b.".$vl] = $val;
                        } else {
                            if ( $val != "")  $where["a.".$vl] = $val;
                        }
                        break;
                    case 'i-':
                        $st = substr($vl, 0, 2);
                        if( $st == "b-"){
                            $vl = substr($vl, 2);
                            if (!empty($val)) $where["b.".$vl] = ['in',$val];
                        } else {
                            if (!empty($val)) $where["a.".$vl] = ['in',$val];
                        }
                        break;
                    default:
                        break;
                }
            }
            $stime = $this->request->param('stime', 0);
            $ltime = $this->request->param('ltime', 0);
            if (empty($stime) && !empty($ltime)) {
                $ltime = strtotime($ltime);
                $where['a.create_time'] = ['<=', $ltime];
            }
            if (!empty($stime) && empty($ltime)) {
                $stime = strtotime($stime);
                $where['a.create_time'] = ['>', $stime];
            }
            if (!empty($stime) && !empty($ltime)) {
                $ltime = strtotime($ltime);
                $stime = strtotime($stime);
                $where['a.create_time'] = ['between', [$stime, $ltime]];
            }
            $page = $page - 1;
            if( count( $this->join ) > 0 ){
                $list = $this->model
                    ->alias('a')
                    ->join( $this->join )
                    ->field($this->field)
                    ->where($where)
                    ->limit($page * $per, $per)
                    ->order($this->order)
                    ->select();
                $sql = $this->model->getLastSql();
                $count = $this->model->alias('a')->join( $this->join )->where($where)->count();
            } else {
                $list = $this->model
                    ->alias('a')
                    ->field($this->field)
                    ->where($where)
                    ->limit($page * $per, $per)
                    ->order($this->order)
                    ->select();
                $sql = $this->model->getLastSql();
                $count = $this->model->alias('a')->where($where)->count();
            }
            $data = [
                'list' => $list,
                'count' => $count,
                'sql'   => $sql
            ];
            $this->success('获取成功！', '', $data);
        }
        return $this->fetch();
    }

    /**
     * @return mixed|void
     * 添加方法
     */
    public function add()
    {
        if (request()->isPost() && request()->isAjax()) {
            $data = $this->request->param();
            if( $this->isValidate ){
                $validate = validate($this->table);
                if (!$validate->check($data)) {
                    $this->error($validate->getError());
                }
            }
            $res = $this->model->allowField( true )->data( $data )->isUpdate( false )->save();
            if ($res) {
                operaLog($this->admin_id.'添加成功');
                $this->success('新增成功');

            }
            $this->error('新增失败！');
        }
        return $this->fetch();
    }

    /**
     * @return mixed|void
     * 修改方法
     */
    public function edit()
    {
        $id = $this->request->param('id', 0, 'intval');
        if (empty($id)) {
            $this->error("参数错误！");
        }
        if (request()->isPost() && request()->isAjax()) {
            $data = $this->request->param();
            if( $this->isValidate ){
                $validate = validate($this->table);
                if (!$validate->check($data)) {
                    $this->error($validate->getError());
                }
            }
            $res = $this->model->allowField( true )->isUpdate( true )->data( $data )->save();
            if ($res) {
                operaLog($this->admin_id.'edit编辑');
                $this->success('修改成功');
            }
            $this->error('修改失败！');
        }
        $data = $this->model->where('id', $id)->find();
        $this->assign('data', $data);
        return $this->fetch();
    }

    /**
     * 修改数据的状态值
     */
    public function changeStatus()
    {
        if (request()->isAjax() && request()->isPost()) {
            $id = request()->param('id', 0, 'intval');
            if (empty($id)) {
                $this->error('参数错误');
            }
            $value = request()->param('value', 0, 'intval');
            $where = ['id' => $id];
            $field = $this->request->param('field', 'status');
            $res = $this->model->where($where)->update([ $field => $value]);
            if ($res) {
                operaLog($this->admin_id.'修改状态');
                $this->success('设置成功！');


            } else {
                $this->error('请重新设置！');
            }
        }
        $this->error('非法请求类型！');
    }

    /**
     * 伪删除
     */
    public function remove()
    {
        if (request()->isAjax() && request()->isPost()) {
            $id = request()->param('id', 0, 'intval');
            if (empty($id)) {
                $this->error('参数错误');
            }
            $where = ['id' => $id];
            $res = $this->model->where($where)->update(['delete_time' => time()]);
            if ($res) {
                into_recycle($id, $this->table);
                operaLog($this->admin_id.'伪删除');
                $this->success('移除成功！');
            } else {
                $this->error('系统繁忙！请稍后再试');
            }
        }
        $this->error('非法请求类型！');
    }

    /**
     * 2019/6/6 0006 9:41
     * @desc 真实删除
     * @ApiParams
     * @ApiReturnParams
     */
    public function delete()
    {
        if (request()->isAjax() && request()->isPost()) {
            $id = request()->param('id/a', 0, 'intval');
            if (empty($id)) {
                $this->error('参数错误');
            }
            $where = ['id' =>['in',$id]];
            $res = $this->model->where($where)->delete();
            if ($res) {
                operaLog($this->admin_id.'真实删除');
                $this->success('删除成功！');
            } else {
                $this->error('系统繁忙！请稍后再试');
            }
        }
        $this->error('非法请求类型！');
    }

    /**
     * 排序
     */
    public function sortOrder()
    {
        $id = $this->request->param('id', 0, 'intval');
        if (empty($id)) {
            $this->error('参数错误');
        }
        if (request()->isPost() && \request()->isAjax()) {
            $value = $this->request->param('value', 0, 'intval');
            $this->model->where('id', $id)->update(['sort' => $value]);
            operaLog($this->admin_id.'排序设置');
            $this->success('设置成功！');
        }
        $this->error('请求方式错误！');
    }
    /*
     * 分页计算
     * */
    public function limit_on($page,$pageSize=null) {
        if ($pageSize===null) {
            $this->_limit = $page;
        }
        else {
            $pageval = intval( ($page - 1) * $pageSize);
            $this->_limit = $pageval.",".$pageSize;
        }

        return $this;
    }
    /**
     * @return string
     * 生成16位订单号
     */


}