<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/14 0014
 * Time: 13:19
 */

namespace app\daili\controller;
use app\common\controller\QRcode as QR;
use app\common\controller\Business;
use think\Db;

/**
 * @desc 后端首页
 * Class Index
 * @package app\manage\controller
 */
class Daili extends Business
{
    private $pre = "ss";
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        $this->pre = config("database.prefix");
        $this->table = "business";
        $this->model = model( "business");
    }


    public function index()
    {
        if (($apply = $this->checkDaili()) !== true){
            return $apply;
        }
        if ($this->request->isGet()) {
            $id = $this->request->param('id', session('business')['id']);
            if (!$id) {
                $this->error('路径错误');
            }
            $this->assign( 'id', $id);
        }
        return parent::index();
    }


    public function account_log()
    {
        $id = $this->request->param('id',0,'intval');
        if( empty( $id )){
            $this->error( "商户参数错误！");
        }
        if( request()->isAjax() && request()->isPost() )
        {
            $page = $this->request->param('page', 1, 'intval');
            $per = $this->request->param('limit', 10, 'intval');
            $this->order = $this->request->param('order', $this->order);
            $param = $this->request->param();
            $where = [ 'a.bus_id' => $id ];
            foreach ($param as $key => $val) {
                $ps = substr($key, 0, 2);
                $vl = substr($key, 2);
                switch ($ps) {
                    case 'l-':
                        if (!empty($val)) $where["b.".$vl] = ['like', '%' . $val . '%'];
                        break;
                    case 'e-':
                        if (!empty($val))  $where["a.".$vl] = $val;
                        break;
                    case 'i-':
                        if (!empty($val)) $where["a.".$vl] = ['in',$val];
                        break;
                    default:
                        break;
                }
            }
            $stime = $this->request->param('stime', 0);
            $ltime = $this->request->param('ltime', 0);
            if (empty($stime) && !empty($ltime)) {
                $ltime = strtotime($ltime);
                $where["a.".'create_time'] = ['<=', $ltime];
            }
            if (!empty($stime) && empty($ltime)) {
                $stime = strtotime($stime);
                $where["a.".'create_time'] = ['>', $stime];
            }
            if (!empty($stime) && !empty($ltime)) {
                $ltime = strtotime($ltime);
                $stime = strtotime($stime);
                $where["a.".'create_time'] = ['between', [$stime, $ltime]];
            }
            $page = $page - 1;
            $list = model('AccountLog')
                ->alias('a')
                ->join( [
                    [config('database.prefix').'business b', 'b.id = a.bus_id','left'],
                    [config('database.prefix').'user c', 'c.id = a.user_id','left'],
                ])
                ->field( "a.info,a.desc,a.account,a.create_time,a.id,b.name,c.nickname")
                ->where($where)
                ->limit($page * $per, $per)
                ->order($this->order)
                ->select();
            $sql = $this->model->getLastSql();
            $count = model('AccountLog')
                ->alias('a')
                ->join( [
                    [config('database.prefix').'business b', 'b.id = a.bus_id','left'],
                    [config('database.prefix').'user c', 'c.id = a.user_id','left'],
                ])
                ->where($where)
                ->count();
            $data = [
                'list' => $list,
                'count' => $count,
                'sql'   => $sql
            ];
//            halt($data);
            $this->success('获取成功！', '', $data);
        }
        $this->assign("id", $id);
        return $this->fetch();
    }


    public function passageway()
    {
        $id = $this->request->param('id',0,'intval');
        if( empty( $id )){
            $this->error( "商户参数错误！");
        }
        if( request()->isAjax() && request()->isPost() )
        {
            $page = $this->request->param('page', 1, 'intval');
            $per = $this->request->param('limit', 10, 'intval');
            $this->order = $this->request->param('order', $this->order);
            $where = [
                'a.status'    => 1,
                'a.delete_time'   => 0
            ];
            $ns = "(select * from ".config("database.prefix")."user_passageway where business_id =".$id.")";
            $list = model('passageway')
                ->alias('a')
                ->field('a.id,a.name,a.pay_type,a.rate,b.rate uRate,b.cost,b.status,b.id uid,b.business_id')
                ->join([
                    [ $ns.' b','b.passageway_id = a.id','left'],
                ])
                ->where( $where )
                ->limit( ($page-1)*$per, $per)
                ->order( 'a.id asc' )
                ->select();
            $sql = $this->model->getLastSql();
            $count = model('passageway')
                ->alias('a')
                ->where($where)
                ->count();
            $data = [
                'list' => $list,
                'count' => $count,
                'sql'   => $sql
            ];
            $this->success('获取成功！', '', $data);
        }
        $this->assign('pay_type', config('pay_type'));
        $this->assign("id", $id);
        return $this->fetch();
    }

    public function qrcode()
    {
        $id = $this->request->param( 'id', 0, 'intval');
        if( empty( $id ) ){
            exit( "参数错误！");
        }
        $png = 'qrcode/qrcode_'.$id.'.png';
        //判断是否存在二维码
        if( !file_exists( $png ) ){
            $url = input('server.REQUEST_SCHEME') . '://' . input('server.SERVER_NAME');
//            $url = input('server.SERVER_NAME');
            $param = "/index/Api/index?id=".$id;
            $value = $url.$param;
            $errorCorrectionLevel = 'H';//容错级别
            $matrixPointSize = 6;//生成图片大小
            //生成二维码图片
            QR::png($value, 'qrcode.png', $errorCorrectionLevel, $matrixPointSize, 1);
            $logo = 'static/manage/images/i4.png';//准备好的logo图片
            $QR = 'qrcode.png';//已经生成的原始二维码图
            if ($logo !== FALSE) {
                $QR = imagecreatefromstring(file_get_contents($QR));
                $logo = imagecreatefromstring(file_get_contents($logo));
                $QR_width = imagesx($QR);//二维码图片宽度
                $QR_height = imagesy($QR);//二维码图片高度
                $logo_width = imagesx($logo);//logo图片宽度
                $logo_height = imagesy($logo);//logo图片高度
                $logo_qr_width = $QR_width / 5;
                $scale = $logo_width/$logo_qr_width;
                $logo_qr_height = $logo_height/$scale;
                $from_width = ($QR_width - $logo_qr_width) / 2;
                //重新组合图片并调整大小
                imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
            }
            //输出图片
            imagepng($QR, $png);
            if( file_exists( "qrcode.png") ){
                unlink( "qrcode.png" );
            }
        }
        echo '<img src="/'.$png.'">';
    }


    public function order()
    {
        if (($apply = $this->checkDaili()) !== true){
            return $apply;
        }
        if ($this->request->isPost() && $this->request->isAjax()) {
            $this->model = model('order');
            $page = $this->request->param('page', 1, 'intval');
            $per = $this->request->param('limit', 10, 'intval');
            $this->order = $this->request->param('order', $this->order);
            $where = [
                'delete_time' => 0,
            ];
            $param = $this->request->param();
            foreach ($param as $key => $val) {
                $ps = substr($key, 0, 2);
                $vl = substr($key, 2);
                switch ($ps) {
                    case 'l-':
                        if (!empty($val)) $where[$vl] = ['like', '%' . $val . '%'];
                        break;
                    case 'e-':
                        if (!empty($val)) $where[$vl] = $val;
                        break;
                    case 'i-':
                        if (!empty($val)) $where[$vl] = ['in', $val];
                        break;
                    default:
                        break;
                }
            }
            $defaulttime = $this->request->param('defaulttime');
            if (empty($defaulttime)) {
                $stime = $this->request->param('stime', 0);
                $ltime = $this->request->param('ltime', 0);
                if (empty($stime) && !empty($ltime)) {
                    $ltime = strtotime($ltime);
                    $where['create_time'] = ['<=', $ltime];
                }
                if (!empty($stime) && empty($ltime)) {
                    $stime = strtotime($stime);
                    $where['create_time'] = ['>', $stime];
                }
                if (!empty($stime) && !empty($ltime)) {
                    $ltime = strtotime($ltime);
                    $stime = strtotime($stime);
                    $where['create_time'] = ['between', [$stime, $ltime]];
                }
            } else {
                $this->model->whereTime('create_time', $defaulttime);
            }
            $business_name = $this->request->param('business_name');
            $business_where = ['top_id' => session('business')['id']];
            if (!empty($business_name)) {
                $business_where['name'] = ['like', '%' . $business_name . '%'];
            }
            $business = model('business')->where($business_where)->field("id")->select();
            if (!empty($business)) {
                $where['business_id'] = ['in', array_column($business, 'id')];
            } else {
                $this->error('找不到商户订单');
            }
            $business_id = $this->request->param('business_id');
            !empty($business_id) && $where['business_id'] = $business_id;
            $passageway_id = $this->request->param('passageway_id');
            if (!empty($passageway_id)) {
                $passageway = model('user_passageway')->where(['passageway_id' => $passageway_id])->field(["id","business_id"])->select();
                if (!empty($passageway)) {
                    $where['business_id'] = ['in', array_column($passageway, 'business_id')];
                    $where['user_passageway_id'] = ['in', array_column($passageway, 'id')];
                } else {
                    $this->error('通道错误');
                }
            }
            $page = $page - 1;
            $list = $this->model
                ->field($this->field)
                ->where($where)
                ->limit($page * $per, $per)
                ->order($this->order)
                ->select();
            $sql = $this->model->getLastSql();
            $this->model->where($where);
            !empty($defaulttime) && $this->model->whereTime('create_time', $defaulttime);
            !empty($business_name) && $where['business_id'] = ['in', array_column($business, 'id')];
            $count = $this->model->count();
            $data = [
                'list' => $list,
                'count' => $count,
                'sql' => $sql
            ];
            $this->success('获取成功！', '', $data);
        }
        $passageway_list = Db::name('passageway')->field(['id','name'])->select();
        $this->assign('passageway_list', $passageway_list);
        return $this->fetch();
    }

    public function checkDaili()
    {
        if (session('business')['is_daili'] == 1){
            return true;
        }
        return $this->fetch('apply_daili');
    }

    public function daili_apply()
    {
        if (request()->isAjax() && request()->isPost()) {
            $id = session('business')['id'];
            if (empty($id)) {
                $this->error('系统错误');
            }
            $desc = request()->param('desc', '');
            $res = model('DailiApply')->insert([ 'desc' => $desc, 'business_id' => $id]);
            if ($res) {
                $this->success('提交成功');
            }
            $this->error('提交失败');
        }
        $this->error('非法请求类型！');
    }

    // 收益
    public function income()
    {
        $list = model('Order');

        $id = session('business')['id'];
        $this->assign('id', $id);
        return $this->fetch();
    }
}