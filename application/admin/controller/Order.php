<?php

namespace app\admin\controller;

use controller\BasicAdmin;
use service\DataService;
use think\Db;
use think\Request;
use model\Order as OrderModel;

/**
 * Order
 * @author    ShaoWei Pu <pushaowei0727@gmail.com>
 * @date      2017/6/24
 * @package   app\admin\controller
 * @license   Mozilla
 */
class Order extends BasicAdmin
{
    public $table = 'EschoolOrder';

    public function index(){
        $this->title = '订单列表';
        // 获取到所有GET参数
        $get = $this->request->get();
        // 实例Query对象
        $list = new OrderModel();
        $list->alias('main')
                ->field(['main.id','main.status','main.pay_way','main.create_time','main.increment_id',
                         'u.name','SUM(main.pay_money) as pay_money','a.id as apply_id','a.apply_type',
                         'm.m_topic','a.apply_name','m.end_time','m.apply_time','m.m_status',
                         'u.mobile','su.organ'])
                ->join([
                    ['__ESCHOOL_USER__ u'  ,'main.user_id = u.id'],
                    ['__ESCHOOL_MEETING__ m','main.m_id = m.id'],
                    ['__ESCHOOL_APPLY__ a','a.m_id = m.id'],
                    ['system_user su','su.id = m.o_id'],
                ],null,'LEFT')->group('main.increment_id');

        /* ---------------- 会议相关 ----------------- */
        foreach (['mobile'] as $key) {
            if (isset($get[$key]) && $get[$key] !== '') {
                $list->where('u.'.$key, 'like', "%{$get[$key]}%");
            }
        }
        /* ---------------- 订单相关 ----------------- */

        // 实例化并显示
        return parent::_list($list);
    }

    /**
     * 订单详情
     * @return array|string
     */
    public function details(){
        $this->title = '订单状态';
        if($this->request->isPost()){
            $post = $this->request->post();
            $result = Db::name($this->table)
                ->where('increment_id', $post['increment_id'])
                ->update(
                    [
                        'status'         => $post['status'],
                        'operation_name' => $post['operation_name']
                    ]);
            if (false !== $this->_callback('_form_result', $result)) {
                $result !== false ? $this->success('恭喜, 数据保存成功!', '') : $this->error('数据保存失败, 请稍候再试!');
            }
        }
        $increment_id = intval($this->request->get('increment_id',''));
        $vo = Db::name($this->table)
                ->field(['SUM(pay_money) as pay_money','increment_id','pay_way','create_time','status','operation_name'])
                ->where('increment_id',$increment_id)
                ->find();
        return $this->fetch('form',compact('vo'));
    }

}