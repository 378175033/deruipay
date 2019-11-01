<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/7/9 0009
 * Time: 17:57
 */

namespace app\daili\controller;

use app\common\controller\Business;
use think\Request;

class Pay extends Business
{
    protected static $controller;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        if ($passageway = $request->param('passageway', 0)) {
            self::$controller = controller($passageway);
        }else{
            $this->error('need passageway!');
        }
    }

    public static function pay()
    {
        self::$controller->pay();
    }


}