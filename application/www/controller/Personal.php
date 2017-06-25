<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/25
 * Time: 13:28
 */

namespace app\www\controller;

use think\Db;
use think\Request;
use think\Session;
use think\Controller;

use app\www\model\Order;
use app\www\model\User;

class Personal extends base
{
    public $userId = "";
    public function _initialize()
    {
        // 初始化前置操作
        parent::_initialize();

        if(isset($_COOKIE['userId'])) {
            $this->userId = $_COOKIE['userId'];
        }

        if(empty($this->userId)){
            exit("未登录");
        } else {
            $userInfo = User::get($this->userId);
            $this->assign("userInfo",$userInfo);
        }
    }

    public function index(Request $request)
    {
        if (false === Request::instance ()->isAjax ()) {
            $orderInfo = Db::name('eschool_order')
                ->alias ('o')
                ->field("o.m_id,o.increment_id,o.order_money,o.pay_money,o.create_time")
                ->field("count(t.id) as count,is_check")
                ->field("m.m_topic,m.start_time")
                ->join('eschool_meeting m','m.id=o.m_id','inner')
                ->join('eschool_ticket t','t.order_id = o.id','left')
                ->where('o.user_id','eq',1)
                ->group('o.id')
                ->select();
            foreach($orderInfo as $key=>&$val)
            {
                $val['start_time'] = date('Y-m-d H:i:s',$val['start_time']);
            }
            //$orderInfo = Order::all(function($query) use($userid){
//                $query->where('user_id','eq',$userid);
             //   $query->where('user_id','eq',1);
           // });

            return $this->fetch ('index',['order'=>$orderInfo]);
        }
        switch($request->param('ident')){
            case 1:
                $save_re = User::where('id', $request->param('userid'))
                    ->update([
                        'username' => $request->param('username'),
                        'loginname'=> $request->param('loginname'),
                        'nick' => $request->param('nick')
                    ]);
                break;
            case 2:
                $save_re = User::where('id',$request->param('userid'))
                    ->update([
                        'name' => $request->param('name'),
                        'sex' => $request->param('sex'),
                        'family' => $request->param('family'),
                        'title' => $request->param('title'),
                        'direction' => $request->param('direction'),
                        'company' => $request->param('company'),
                        'director' => $request->param('director'),
                        'zipcode' => $request->param('zipcode'),
                        'address' => $request->param('address')
                    ]);
                break;
        }
        return ['errno'=>200,'success','data'=>''];
    }

    public function loginout()
    {
        setcookie('userId',null,0, '/', null, null);

        $this->redirect('/www/');
    }
}