<?php

namespace app\admin\controller;

use controller\BasicAdmin;
use service\DataService;
use model\Meeting as MeetingModel;
use think\Db;
use think\Request;

/**
 * Meeting
 * @author    ShaoWei Pu <pushaowei0727@gmail.com>
 * @date      2017/6/21
 * @package   app\admin\controller
 * @license   Mozilla
 */
class Meeting  extends BasicAdmin
{
    public $table = 'EschoolMeeting';

    /**
     * 会议管理
     */
    public function index(){
        // 设置页面标题
        $this->title = '会议管理';
        // 获取到所有GET参数
        $get = $this->request->get();
        $list = new MeetingModel();
        $list->alias('main')
            ->field(['main.m_topic','main.start_time','main.m_code','main.status','main.end_time',
                     'main.apply_time', 'su.organ','a.apply_name','main.id','main.m_status',
                     's.id AS s_id','n.id AS n_id'
            ])->join([
                ['__ESCHOOL_APPLY__ a','main.id = a.m_id '],
                ['__ESCHOOL_NOTICE__ n','main.id = n.m_id '],
                ['__ESCHOOL_SCHEDULE__ s','main.id = s.m_id '],
                ['system_user su','main.o_id = su.id'],
            ],null,'LEFT');

        // 应用搜索条件
        foreach (['m_topic'] as $key) {
            if (isset($get[$key]) && $get[$key] !== '') {
                $list->where('main.'.$key, 'like', "%{$get[$key]}%");
            }
        }
        // 实例化并显示
        return parent::_list($list);
    }

    /**
     * 会议操作
     */
    public function meeting(){
        if ($this->request->isGet()) {
            $id = $this->request->get('id','');
            return view('form', ['title' => '发表会议','id'=> $id,'vo' => Db::name($this->table)->where('id',$id)->find()]);
        }
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if ($this->_apply_news_meeting($data['data']) ) {
                return  "<script>window.location='#/meeting/index'</script>";
            }
            $this->error('会议编辑失败，请稍候再试！');
        }
    }

    /**
     * 更新操作
     * @param array $data
     * @param array $ids
     * @return string
     */
    protected function _apply_news_meeting($data) {
        $data['o_id']       = session('user.id');
        $data['start_time'] = strtotime($data['start_time']);
        $data['end_time']   = strtotime($data['end_time']);
        $data['apply_time'] = strtotime($data['apply_time']);
        $data['m_content']  = empty($data['m_content']) ? strip_tags( $data['m_content']) : $data['m_content'];
        if (empty($data['id'])) {
            $result = $id = Db::name($this->table)->insertGetId($data);
        } else {
            $id = intval($data['id']);
            $result = Db::name($this->table)->where('id', $id)->update($data);
        }
        if ($result !== FALSE) {
            return true;
        }
    }

    /**
     * 关闭
     */
    public function forbid(){
        if($this->request->isPost()){
            $data = $this->request->post();
            $result = Db::name($this->table)->where('id', $data['id'])
                        ->update([$data['field'] => $data['value']]);
            if( $result !== FALSE ){
                $this->success('操作成功','');
            }
            $this->error('操作失败');
        }
    }
    /**
     * 会议日程
     */
    public function schedule(){
        $get = $this->request->get();
        if( empty($get['m_name']) || empty($get['m_id'])){
            return  "<script>window.location='#/meeting/index'</script>";
        }
        $this->title = $get['m_name'];
        // 实例Query对象
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if( !empty($data) ) $data = $data['data'];
            if (empty($data['id'])) {
                $result = $id = Db::name('EschoolSchedule')->insertGetId($data);
            } else {
                $id = intval($data['id']);
                $result = Db::name('EschoolSchedule')->where('id', $id)->update($data);
            }
            if ($result !== FALSE) {
                return  "<script>window.location='#/meeting/index'</script>";
            }else{
                $this->error('通知编辑失败，请稍候再试！');
            }
        }
        $this->assign(['m_id' => $get['m_id']]);
        return $this->_form('EschoolSchedule',null,'m_id',['m_id' => $get['m_id']]);
    }

    /**
     * 通知管理
     */
    public function notice(){
        // 实例Query对象
        $get = $this->request->get();
        if( empty($get['m_name']) || empty($get['m_id'])){
            return  "<script>window.location='#/meeting/index'</script>";
        }
        $this->title = $get['m_name'];
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if( !empty($data) ) $data = $data['data'];
            if (empty($data['id'])) {
                $result = $id = Db::name('EschoolNotice')->insertGetId($data);
            } else {
                $id = intval($data['id']);
                $result = Db::name('EschoolNotice')->where('id', $id)->update($data);
            }
            if ($result !== FALSE) {
                return  "<script>window.location='#/meeting/index'</script>";
            }else{
                $this->error('通知编辑失败，请稍候再试！');
            }
        }
        $this->assign(['m_id' => $get['m_id']]);
        return $this->_form('EschoolNotice',null,'m_id',['m_id' => $get['m_id']]);
    }

    /**
     * 课件管理
     */
    public function courseware(){

    }

    /**
     * 签到管理
     */
    public function sigin(){

    }

    /**
     * 报名管理
     */
    public function apply(){

    }

    /**
     * 会员管理
     */
    public function member(){

    }





}
