<?php
namespace app\admin\controller;

use controller\BasicAdmin;
use service\DataService;
use think\Db;
use think\Request;
use  app\admin\model\Meeting as MeetingModel;

/**
 * Apply
 * @author    ShaoWei Pu <pushaowei0727@gmail.com>
 * @date      2017/6/22
 * @package   app\admin\controller
 * @license   Mozilla
 */
class Apply extends BasicAdmin
{
    public $table = 'EschoolApply';

    public function index(){
        $this->title = '报名详情';
        // 获取到所有GET参数
        $get = $this->request->get();
        // 实例Query对象
        if( empty($get['increment_id']) || empty($get['apply_id']) ){
            return  "<script>window.location='#/meeting/index'</script>";
        }

    }

    /**
     * @return array|string
     */
    public function assoc(){
        $mid    = $this->request->get('m_id','');
        $m_name = $this->request->get('m_name','');
        if( empty($mid) || empty($m_name) ){
            return  "<script>window.location='#/meeting/index'</script>";
        }
        $this->assign([
            'title'=> $m_name,
            'm_id' => $mid,
        ]);
        return $this->_form($this->table, 'assoc','m_id',['m_id'=>$mid]);
    }

    /**
     * 显示手机预览
     * @return string
     */
    public function review() {
        $this->assign([
                'type' => $this->request->get('type', 'form_one')
        ]);
        return view();
    }

    public function details(){

    }
}