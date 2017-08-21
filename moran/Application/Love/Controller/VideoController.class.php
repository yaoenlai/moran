<?php
namespace Love\Controller;
use Home\Controller\HomeController;

class VideoController extends HomeController
{
    public function is_free(){
        if(IS_POST){
            $post = I("post.");
            //判断是否为免费终端
            $is_free = M("LoveSite")->where(array('status'=>'1','id'=>$post['sid']))->getField("isfree");
            if($is_free == '0'){
                //判断注册时间
                $info = M("AdminUser")->field('create_time,gender')->where(array('status'=>'1','id'=>$post['uid']))->find();
                $create_time = $info['create_time'];
                if(($create_time + (80*60*60) - time()) > 0){
                    //判断是否有充值记录
                    $num = M("RechargeIndex")->where(array('uid'=>$post['uid'],'is_pay'=>'1'))->count();
                    if(empty($num)){
                        $list = M()->query("SELECT `nickname`,`avatar` FROM `ue_admin_user` WHERE `status` = 1 AND `gender` NOT IN ('{$info['gender']}','0') AND `avatar` <> '0' ORDER BY RAND() LIMIT 15");
                        $return = array(
                            'list'  => $list,
                            'time'  => $create_time,
                            'msg'   => "该用户没有充值记录或没有确认支付",
                        );
                        $this->error($return,'',true);
                    } else {
                        $this->success("该用户是充值用户",'',true);
                    }
                } else if($create_time == null){
                    $this->error("用户不存在或禁用",'',true);
                } else {
                    $this->success('用户注册已达到80小时','',true);
                }
            } else if($is_free == null) {
                $this->error("终端不存在","",true);
            } else {
                $this->success("终端为免费终端",'',true);
            }
        }
    }
}