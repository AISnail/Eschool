<?php
namespace app\admin\controller;

use controller\BasicAdmin;
use service\DataService;
use think\Db;
use think\Request;
use model\Form as FormModel;


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
        if(  empty($get['increment_id']) ||
             empty($get['apply_type'])   ||
            !in_array($get['apply_type'],FormModel::returnForm())
        ){
            return  "<script>window.location='#/meeting/index'</script>";
        }
        $form  = new FormModel();
        $table = $form->where([
            'increment_id' =>   $get['increment_id'],
            'apply_type'   =>   $get['apply_type'],
        ])->find();
dd(json_encode([
    'master' => [
        'danwei_name' => '北京大保健文化传媒',
        'tx_address'  => '就是那里',
        'lx_mobile'   => '110',
        'zs_yd'       => '1',
        'bz_sm'       => '这他妈还有备注'
    ],
    'slave' => [
            [
              'name' => '张三',
                'sex' => '男',
                'bm_zw' => '管道工',
                'bg_dh' => '13121212121',
                'mobile' => '13121212121',
                'email' => '1@q.cn',
            ],
            [
              'name' => '里斯',
                'sex' => '男',
                'bm_zw' => '管道工',
                'bg_dh' => '13121212121',
                'mobile' => '13121212121',
                'email' => '1@q.cn',
            ],
    ],
]));
        $this->assign([
            'form'  => dirname(__DIR__).DS.'view'.DS.$table['apply_type'].'.html',
            'table' => $table['apply_json'],
            'down'  => $table['down_path']
        ]);
        return view();
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