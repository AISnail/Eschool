<?php
namespace app\www\controller;


use think\Request;

class meeting extends base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(Request $request)
    {
        /* --------------- 机构 --------------- */
        $organ_array = [
            ['id' => 1, 'name'=>'中国高等教育学会'],
            ['id' => 2, 'name'=>'中国高等教育学会分支机构'],
            ['id' => 3, 'name'=>'超神机构'],
            ['id' => 4, 'name'=>'0牛逼机构'],
            ['id' => 5, 'name'=>'1牛逼机构'],
            ['id' => 6, 'name'=>'2牛逼机构']
        ];

        $old_array    = $organ_array;
        $hidden_organ = array_splice($organ_array,2);
        $organ_id     = $request->param('organ',1);
        $a_key        = array_search($organ_id, array_column($old_array, 'id'));
        $organ_title  = $old_array[$a_key ?? 0]['name'];

        /* --------------- 会议 --------------- */


        return $this->fetch('index',compact('organ_title','organ_array','old_array','hidden_organ','organ_id'));
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
