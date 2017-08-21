<?php
// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------
namespace Love\Controller;
use Home\Controller\HomeController;
use Common\Util\Think\Page;
/**
 * 关注控制器
 * @author zxq
 */
class FollowController extends HomeController{
    /**
     * 初始化方法
     * @author zxq
     */
    protected function _initialize(){
        parent::_initialize();
        $this->is_login();
    }

    /**
     * 关注粉丝
     * @param $tab 配置分组ID
     * @author zxq
     */
    public function index($type = 1) {
        // 获取列表
        switch ($type) {
            case '1' :
                $map['status'] = array('eq', '1');  // 禁用和正常状态
                $map['follow_uid']  = $this->is_login();
                $p = !empty($_GET["p"]) ? $_GET['p'] : 1;
                $follow_object = D('Follow');
                $data_list = $follow_object
                           ->page($p, C('ADMIN_PAGE_ROWS'))
                           ->where($map)
                           ->order('id asc')
                           ->select();
                $page = new Page(
                    $follow_object->where($map)->count(),
                    C('ADMIN_PAGE_ROWS')
                );

                foreach ($data_list as &$data) {
                    $user = D('Admin/User')->getUserInfo($data['uid']);
                    if ($user) {
                        $data['user'] = $user;
                    } else {
                        unset($data);
                    }
                    
                }

                $this->assign('data_list', $data_list);
                $this->assign('meta_title', "我关注的人");
                break;
            case '2' :
                $map['status'] = array('eq', '1');  // 禁用和正常状态
                $map['uid']  = $this->is_login();
                $p = !empty($_GET["p"]) ? $_GET['p'] : 1;
                $follow_object = D('Follow');
                $data_list = $follow_object
                           ->page($p, C('ADMIN_PAGE_ROWS'))
                           ->where($map)
                           ->order('id asc')
                           ->select();
                $page = new Page(
                    $follow_object->where($map)->count(),
                    C('ADMIN_PAGE_ROWS')
                );

                foreach ($data_list as &$data) {
                    $user = D('Admin/User')->getUserInfo($data['follow_uid']);
                    if ($user) {
                        $data['user'] = $user;
                    } else {
                        unset($data);
                    }
                }

                $this->assign('data_list', $data_list);
                $this->assign('meta_title', "我的粉丝");
                break;
        }
        $this->display();

    }

    /**
     * 关注用户
     * @param $type 消息类型
     * @author zxq
     */
    public function add($uid){
        $follow_object = D('User/Follow');
        $con['uid'] = $uid;
        $con['follow_uid'] = $this->is_login();
        if ($con['uid'] === $con['follow_uid']) {
            $this->error('非法操作');
        }
        $find = $follow_object->where($con)->find();
        if ($find) {
            if ((time() - $find['update_time']) < 10) {
                $this->error('操作频繁，10秒后重试！');
            }
            if ($find['status'] === '1') {
                $where['id'] = $find['id'];
                $result = $follow_object
                        ->where($where)
                        ->setField(array('status' => 0, 'update_time' => time()));
                if ($result) {
                    $return['status'] = 1;
                    $return['info'] = '取消关注成功'.$follow_object->getError();
                    $return['follow_status'] = 0;
                    $this->ajaxReturn($return);
                } else {
                    $return['status'] = 0;
                    $return['info'] = '取消关注失败'.$follow_object->getError();
                    $return['follow_status'] = 1;
                    $this->ajaxReturn($return);
                }
            } else {
                $where['id'] = $find['id'];
                $result = $follow_object
                        ->where($where)
                        ->setField(array('status' => 1, 'update_time' => time()));
                if ($result) {
                    $return['status'] = 1;
                    $return['info'] = '关注成功'.$follow_object->getError();
                    $return['follow_status'] = 1;
                    $this->ajaxReturn($return);
                } else {
                    $return['status'] = 0;
                    $return['info'] = '关注失败'.$follow_object->getError();
                    $this->ajaxReturn($return);
                }
            }
        } else {
            $data = $follow_object->create($con);
            if ($data) {
                $result = $follow_object->add($data);
                if ($result) {
                    $return['status'] = 1;
                    $return['info'] = '关注成功'.$follow_object->getError();
                    $return['follow_status'] = 1;
                    $this->ajaxReturn($return);
                } else {
                    $return['status'] = 0;
                    $return['info'] = '关注失败'.$follow_object->getError();
                    $this->ajaxReturn($return);
                }
            }
        }
    }
}
