<?php
namespace app\www\controller;


use think\Db;
use think\Request;
use model\Meeting as MeetingModel;
class meeting extends base
{
    public $user_table = 'SystemUser';

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
        $organ_id     = $request->param('organ',1);
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
        return $this->fetch('notice',[
            'mid'   => $this->mid,
        ]);
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
           return $this->fetch('apply',[
               'mid'   => $this->mid,
           ]);
       }
    }
}
