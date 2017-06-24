<?php

namespace model;

use think\Model;

class Order extends Model
{
    protected $table = 'eschool_order';

    /**
     * 对ticket 1 对 1
     * @return \think\model\relation\HasOne
     */
    public function ticket(){
        return $this->hasOne('ticket');
    }

    public function user(){
        return $this->hasOne('user','id');
    }

    /**
     * status 获取器
     * @param $value
     * @return mixed
     */
    public function getStatusAttr($value)
    {
        $status = ['未支付','已支付','待审核'];
        return $status[$value];
    }

    /**
     * pay 获取器
     * @param $value
     * @return mixed
     */
    public function getPayWayAttr($value)
    {
        $pay_way = [
            'xianxia'   => '线下付款',
            'duigong'   => '对公转账',
            'alipay'    => '支付宝',
            'weipay'    => '微信',
        ];
        return $pay_way[$value];
    }
}

