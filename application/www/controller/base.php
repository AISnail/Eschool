<?php

namespace app\www\controller;

use app\www\helper\StaticInit;
use think\Controller;
use think\Request;

use app\www\model\User;

/**
 * base
 * @author    ShaoWei Pu <pushaowei0727@gmail.com>
 * @date      2017/6/20
 * @package   app\www\controller
 * @license   Mozilla
 */
class base extends Controller
{
    use StaticInit;
    public $mid;
    public $organ;

    public $userId = "";

    /**
     * 前置操作
     * @param \think\Request $request
     */
    public function _initialize()
    {
        // 初始化前置操作
        parent::_initialize();

        if(isset($_COOKIE['userId'])) {
            $this->userId = $_COOKIE['userId'];
        }

        if(empty($this->userId)){
            echo "<script>alert('未登录！');</script>";
        } else {
            $userInfo = User::get($this->userId);
            $this->assign("userInfo",$userInfo);
        }

        $_request = Request::instance();
        $page     = strtolower($_request->controller().'.'.$_request->action() );
        $this->mid      = $_request->param('mid',1);
        $this->organ    = $_request->param('organ');
        $this->assign('options', $this->options($page));
        $this->_initNav();
        $this->_initUser();
    }

    /**
     * 初始化导航栏及所属下级
     */
    public function _initNav()
    {

    }

    /**
     * 初始化用户
     */
    public function _initUser()
    {

    }

	protected function baseMsgcode() {
        $chars = '0123456789';
        mt_srand((double) microtime() * 1000000 * getmypid());
        $CheckCode = "";
        while (strlen($CheckCode) < 6)
            $CheckCode.=substr($chars, (mt_rand() % strlen($chars)), 1);
        return $CheckCode;
    }

}