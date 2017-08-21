<?php
namespace Yyadmin\Controller;

use Think\Exception;
class UserController extends AdminController
{
    public function index(){
        
        $model = M('YyadminUser'); // 实例化User对象
        $where = array(
            'status'    => '1',
        );
       if(!empty(I("username"))){
          $where['username'] = array("LIKE", "%".I("username")."%"); 
       }
       if(!empty(I('grade'))){
           $where['grade'][] = array('EQ', I("grade"));
       }
       switch (session("admin_user.grade")){
           case '1':
               $where['grade'][] = array("IN", "1,2,3");
               $where['grade'][] = "AND";
               break;
           case '2':
               $where['grade'][] = array("IN", "2,3");
               $where['grade'][] = "AND";
               break;
           case '3':
               $where['grade'][] = array("IN", "3");
               $where['grade'][] = "AND";
               break;
       }
        $count      = $model->where($where)->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $model->where($where)->order('grade')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }
    
    public function del($id){
        if(IS_AJAX){
            $model = M("YyadminUser");
            $where = array(
                'uid'   => $id,
            );
            if($model->where($where)->delete()){
                $this->success("删除成功");
            } else {
                $this->error("删除失败");
            }
        }
    }
    
    public function add(){
        if(IS_POST){
            $model = D("YyadminUser");
            if($model->create('','1')){
                $model->password = md5('123456');
                if($model->add()){
                    $this->success("添加成功");
                } else {
                    $this->error($model->getError());
                }
            } else {
                $this->error($model->getError());
            } 
        } else {
            $this->display();
        }
    }
    
    public function edit($id){
        if(IS_POST){
            $model = D("YyadminUser");
            if($model->create('','2')){
                $where = array(
                    'status'    => '1',
                    'uid'       => $id,
                );
                try {
                    if($model->where($where)->save()){
                        $this->success("修改成功");
                    } else {
                        $this->error($model->getError());
                    }
                } catch(Exception $e) {
                    $this->error($e->getMessage());
                }
            } else {
                $this->error($model->getError());
            }
        } else {
            //获取用户信息
            $info = M("YyadminUser")->field("uid,username,grade,sid")->where(array('status'=>'1','uid'=>$id))->find();
            $this->assign('info', $info);
            //获取可选sid列表
            $where = array(
                'status'    => '1',
            );
            if(session('admin_user.grade') != '1'){ $where['id'] = array('IN', session('admin_user.sid'));}
            $sidList = M("LoveSite")->where($where)->select();
            $this->assign('sidList', $sidList);
            //获取选择sid
            $this->assign('sid', explode(',', $info['sid']));
            
            $this->display();
        }
    }
}