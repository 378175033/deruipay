<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/18 0018
 * Time: 14:05
 *  支付方式
 *
 */
namespace app\manage\controller;
use app\common\controller\Manage;
use app\manage\service\QrcodeService;


class PayModl extends Manage
{
//    static protected $payBabay;
    public function _initialize()
    {
//        parent::_initialize(); // TODO: Change the autogenerated stub
        $this->table = "passageway";
        $this->model = model("passageway");
        if( session("?userInfo") ){
            $this->admin_id = '管理员id:'.session('userInfo')->id.'>>';
        }
    }


    public function get4select()
    {
        $passageway_list = $this->model->where('delete_time', '=', 0)->column('name', 'id');
        return $passageway_list;
    }

    /**
     * Created by PhpStorm
     * User: 赵蓝
     * Date: 2019/6/28 0028
     * Time: 17:41
     * @desc
     * @return mixed
     */
    public function free_pay()
    {
        if( $this->request->isPost() && $this->request->isAjax() )
        {
            //保存固定金额二维码
            $data = $this->request->param();
            if( empty( $data['pay_url'] ) ) $this->error( "二维码链接不能为空！");
            if( empty( $data['price'] ) ) $this->error( "定额二维码价格不能为空！");
            $res = model( 'Qrcode')->allowField( true )->isUpdate( false )->save( $data );
            if( $res ){
                $this->success( "保存成功！");
            }
            $this->error( "保存失败！");
        }
        $pay_type = $this->request->param( 'type', '');
        switch ( $pay_type ){
            case "free_wechat":
                $type = 1;
                break;
            case "free_alipay":
                $type = 2;
                break;
            default:
                $type = 0;
                $this->error( "参数信息错误！");
                break;
        }
        $this->assign( "type", $type);
        return $this->fetch();
    }

    /**
     * Created by PhpStorm
     * User: 赵蓝
     * Date: 2019/6/28 0028
     * Time: 17:47
     * @desc
     * @throws \think\Exception
     * @return mixed
     */
    public function free_pay_list()
    {
        if( $this->request->isPost() && $this->request->isAjax() )
        {
            $where = [
                'type'  => $this->request->param('type', 0, 'intval')
            ];
            $page = $this->request->param('page', 1, 'intval');
            $per = $this->request->param('limit', 10, 'intval');
            $list = model( 'qrcode')
                ->where( $where )
                ->limit( ($page-1)*$per, $per)
                ->order( 'id desc')
                ->select();
            $sql = $this->model->getLastSql();
            $count = model( 'qrcode')->where($where)->count();
            $data = [
                'list' => $list,
                'count' => $count,
                'sql'   => $sql
            ];



            $this->success('获取成功！', '', $data);
        }
        $pay_type = $this->request->param( 'type', '');
        switch ( $pay_type ){
            case "free_wechat":
                $type = 1;
                break;
            case "free_alipay":
                $type = 2;
                break;
            default:
                $type = 0;
                $this->error( "参数信息错误！");
                break;
        }
        $this->assign( 'type', $type);
        return $this->fetch();
    }

    /**
     * Created by PhpStorm
     * User: 赵蓝
     * Date: 2019/6/28 0028
     * Time: 17:47
     * @desc
     * @param $url
     * @return \think\Response
     */
    public function enQrcode($url){
        $qr_code = new QrcodeService(['generate'=>"display","size",200]);
        $content = $qr_code->createServer($url);
        return response($content,200,['Content-Length'=>strlen($content)])->contentType('image/png');
    }

    /**
     * Created by PhpStorm
     * User: 赵蓝
     * Date: 2019/6/28 0028
     * Time: 17:47
     * @desc
     * @return void
     */
    public function process()
    {
        $data = $this->request->param();
        $test = new \pay\qrcode\Test();
        $this->success( "成功！",'',$test->index( $data ) );
    }

    /**
     * 2019/7/15 0015 10:31
     */
    public function rate(){
        return $this->fetch();
    }

    public function edit()
    {
       if($this->request->isPost() && $this->request->isAjax()){
            if($this->request->param('rate')>=1){
                $this->error('通道费率不能大于1');
            }
       }
       return parent::edit(); // TODO: Change the autogenerated stub
    }

}