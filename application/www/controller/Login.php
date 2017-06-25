<?php

namespace app\www\controller;

use think\Db;
use think\Request;
use think\Session;
use think\Controller;

use app\www\model\User;

class Login extends base
{
	public function _initialize()
    {
        // 初始化前置操作
        parent::_initialize();
	}

    public function doLogin(Request $request)
    {
        $username = $request->param('username');
        $password = $request->param('password');

        $info = Db::table('eschool_user')
            ->where('username','eq',$username)
            ->whereOr('loginname','eq',$username)
            ->find();

        if(!empty($info)){
            if($info['password'] == md5($password)){
                $cookie_time=time()+3600*24*7;
                setcookie('userId',$info['id'],$cookie_time, '/', null, null);
                return ['errno'=>200,'msg'=>'success','data'=>url('/www/personal/index',['userid'=>$info['id']])];
            } else {
                return ['errno'=>201,'msg'=>'密码错误','data'=>''];
            }
        } else {
            return ['errno'=>202,'msg'=>'用户不存在','data'=>''];
        }
    }
}
