<?php
namespace Yyadmin\Controller;

class CountController extends PublicController
{
    public function _initialize()
    {
        //获取拥有的sid列表
        $where = array(
            'status'    => '1',
        );
        if(session("admin_user.grade") != '1')
        {
            $where['id'] = array('IN', session("admin_user.sid"));
        }
        $sidList = M("LoveSite")->where($where)->select();
        $this->assign('sidList', $sidList);
    }
    
    public function index(){
        $model = D("LoveSite");
        
        if(IS_POST){
            $post = I("post.");
            $this->assign('post', $post);
            
            $where = $model->wheres($post);
        } else {
            $where = $model->wheres();
        }
        
        //开始时间
        if(!empty($post['create_date'])){
            $create_time = strtotime($post['create_date']);
        } else {
            $create_time = 0;
        }
        //结束时间
        if(!empty($post['end_date'])){
            $end_time = strtotime($post['end_date']);
        } else {
            $end_time = time();
        }
        
        //获取统计data
        $list = $model->field("
                ue_love_site.id,
                ue_love_site.title,
                min(ue_admin_user.create_time) as min_create_time,
                max(ue_admin_user.create_time) as max_create_time,
                count(CASE WHEN ue_admin_user.create_time BETWEEN {$create_time} AND {$end_time} THEN '' END) as number,
                count(CASE WHEN ue_admin_user.gender = 1 AND ue_admin_user.create_time BETWEEN {$create_time} AND {$end_time} THEN '' END) as man,
                count(CASE WHEN ue_admin_user.gender = -1 AND ue_admin_user.create_time BETWEEN {$create_time} AND {$end_time} THEN '' END) as woman,
                Round(SUM(CASE WHEN ue_recharge_index.create_time BETWEEN {$create_time} AND {$end_time} THEN ue_recharge_index.money END) / 100,2) as money,
                Round(SUM(CASE WHEN ue_recharge_index.channel_type = 'alipay' AND ue_recharge_index.create_time BETWEEN {$create_time} AND {$end_time} THEN ue_recharge_index.money END) / 100,2) as ali,
                Round(SUM(CASE WHEN ue_recharge_index.channel_type = 'weixin' AND ue_recharge_index.create_time BETWEEN {$create_time} AND {$end_time} THEN ue_recharge_index.money END) / 100,2) as wx,
                count(CASE WHEN ue_recharge_index.is_pay = '1' AND ue_recharge_index.create_time BETWEEN {$create_time} AND {$end_time} THEN '' END) as pay_num 
                ")
                ->join("ue_love_profile ON ue_love_profile.sid = ue_love_site.id AND ue_love_profile.sid <> 0", "LEFT")
                ->join("ue_admin_user ON ue_admin_user.id = ue_love_profile.uid", "LEFT")
                ->join("ue_recharge_index ON ue_recharge_index.uid = ue_admin_user.id AND ue_recharge_index.`status` = 1 AND ue_recharge_index.is_pay = '1'", "LEFT")
                ->where($where)->group("ue_love_site.id")->select();
        foreach ($list as $key => $value){
            $list[$key]['money1'] = round(($value['wx'] * (100 - 1.2) / 100) + ($value['ali'] * (100 - 0.6) / 100), 2);
        }
        $this->assign('list', $list);
        $this->display();
    }
    public function detail(){
        if(empty(I("create_time"))){
            $create_time = '';
        } else {
            $create_time = I('create_time');
        }
        
        if(empty(I("end_time"))){
            $end_time = time();
        } else {
            $end_time = I('end_time');
        }
        $model = M("RechargeIndex");
        $where = array(
            'status'        => '1',
            'create_time'   => array('BETWEEN', array($create_time,$end_time)),
        );
        $list = $model->where($where)->select();
        $this->assign('list',$list);
        $this->display();
    }
}