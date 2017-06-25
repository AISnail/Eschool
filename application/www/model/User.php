<?php

namespace app\www\model;

use think\Db;
use think\Model;

class User extends Model
{
	protected $table = 'eschool_user';

	public function getUserInfo($username = '')
	{
		//$info = Db::name('eschool_user')->where('username','eq',$username)->whereOr('loginname','eq',$username)->find();
		//return $info;
	}
}
