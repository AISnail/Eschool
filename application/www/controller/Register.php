<?php

namespace app\www\controller;


use think\Db;
use think\Request;
use think\Session;
use think\Controller;

use app\www\model\User;

class Register extends base
{
	public function _initialize()
    {
        // 初始化前置操作
        parent::_initialize();
	}

	public function index(Request $request)
	{
		if (false === Request::instance ()->isAjax ()) {
            return $this->fetch ();
        }

		$username = $request->param('username');
		$password = $request->param('password');
		$email = $request->param('email');
		$mobile = $request->param('mobile');
		$code = $request->param('code');
		$check_re = self::mobileisexits($mobile);

		if(200 != $check_re['errno']){
			return $check_re;
		}

		$check_re = self::checkcode($mobile, $code);
		if(200 != $check_re['errno']){
			return $check_re;
		}


		$save_re = User::create([
			'username' => $username,
			'password' => md5($password),
			'email' => $email,
			'mobile' => $mobile,
			'createtime' => time(),
			'modifytime' => time()
		]);

		if(true == $save_re){
			return ['errno'=>200,'msg'=>'success','data'=>url('/www/register/regisucc',['userid'=>$save_re->id])];
		} else {
			return ['errno'=>201,'msg'=>'注册失败，请重试~','data'=>''];
		}
	}

	public function regisucc(Request $request)
	{
		$userid = $request->param('userid');
		$info = User::get($userid);
		return $this->fetch('regisucc',['info'=>$info]);
	}


	public function getcode(Request $request)
	{
		$mobile = $request->param('mobile');
		$code = $this->baseMsgcode();
		$code = 1;
		Session::set('user_register_code_'.$mobile,$code);
		$get_re = Session::get('user_register_code_'.$mobile);
		if(true == $get_re){
			return ['errno'=>200,'msg'=>'success','data'=>''];
		} else {
			return ['errno'=>201,'msg'=>'验证码获取失败，请稍候重试','data'=>''];
		}
	}

	
	public function mobileisexits($mobile = 0)
	{
		//$mobile = $request->param('mobile');
		$info = User::get(['mobile'=>$mobile]);
		if(!empty($info)){
			return ['errno'=>201,'msg'=>'手机号码已存在','data'=>''];
		}
		return ['errno'=>200,'msg'=>'success','data'=>''];
	}

	public function checkcode($mobile = 0, $code = 0)
	{
		//if (false === Request::instance ()->isAjax ()) {
        //    return $this->fetch ('index');
       // }

		//$mobile = $request->param('mobile');
		//$verifyCode = $request->param('code');

		$verifyCode = Session::get('user_register_code_'.$mobile);

		if($verifyCode == $code){
			return ['errno'=>200,'msg'=>'success','data'=>''];
		} else {
			return ['errno'=>201,'msg'=>'验证码错误','data'=>''];
		}
	}
}