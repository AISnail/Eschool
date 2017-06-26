<?php
namespace app\www\controller;

use controller\Chinese;
use think\Db;
use think\Request;
use model\Meeting as MeetingModel;
use model\Apply as ApplyModel;
class meeting extends base
{
    public $user_table   = 'SystemUser';
    public $notice_table = 'EschoolNotice';

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(Request $request)
    {
        /* --------------- 机构 --------------- */      // 实例Query对象
        $organ_array = Db::name($this->user_table)
                        ->where('is_deleted', '0')
                        ->select();
        $old_array    = $organ_array;
        $hidden_organ = array_splice($organ_array,2);
        $organ_id     = $request->param('organ',10000);
        $a_key        = array_search($organ_id, array_column($old_array, 'id'));
        $organ_title  = $old_array[$a_key ?? 0]['organ'];
       /* --------------- 会议 --------------- */
        $meeting_info  = MeetingModel::where(['o_id' => $organ_id])
                        ->paginate(5);
        $page = $meeting_info->render()  ;
        return $this->fetch('index',compact(
            'organ_title','meeting_info','organ_array',
            'old_array','hidden_organ','organ_id','page')
        );
    }


    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //处理报名逻辑

    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function notice(Request $request)
    {
        $meeting = new MeetingModel();
        $info = $meeting->alias('main')
            ->field(['main.m_topic','main.start_time','main.m_code','main.status','main.end_time',
                'main.apply_time','main.m_content', 'main.id','main.m_status',
                's.s_content','n.n_content','main.red_heade'
            ])->join([
                ['__ESCHOOL_NOTICE__ n','main.id = n.m_id '],
                ['__ESCHOOL_SCHEDULE__ s','main.id = s.m_id '],
            ],null,'LEFT')
            ->where(['main.id' => $this->mid])
            ->find();
        return $this->fetch('notice',[
            'mid'   => $this->mid,
            'info'  => $info
        ]);
    }

    /**
     * php 下载PDF太麻烦了  不搞了
     * @param \think\Request $request
     */
    public function downotice(Request $request){
        $info = Db::name($this->notice_table)
                ->where(['m_id'=>$this->mid])
                ->value('n_content') ?? '';
        header('Content-Type: application/pdf');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.'中国高等教育'.date('Y-m-d H:i:s').'"');
        header('Content-Transfer-Encoding: binary');
        //file_put_contents("php://output",$info);

    }

    /**
     * 会议报名
     */
    public function apply(Request $request)
    {
       if($request->isPost()){

            // 成功跳转支付
            $this->redirect('apply/verify',['aid'=>'1']);
       }else{
            // 根据 Mid 拿到对应的表单
            $info  = (new ApplyModel())->where(['m_id'=>$this->mid])->find();


           return $this->fetch('apply',[
               'mid'       => $this->mid,
               'info'      => $info,
           ]);
       }
    }

}
