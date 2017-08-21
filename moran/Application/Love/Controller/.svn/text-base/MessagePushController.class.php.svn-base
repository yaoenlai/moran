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
 * 推送设备记录控制器
 * @author zxq
 */
class MessagePushController extends HomeController{
    /**
     * 初始化方法
     * @author zxq
     */
    protected function _initialize(){
        parent::_initialize();
        $this->is_login();
    }

    /**
     * 默认方法
     * @param $type 消息类型
     * @author zxq
     */
    public function add($token){
        $data['token'] = $token;
        $data['uid']   = is_login();
        $push_object = D('MessagePush');
        $create_data = $push_object->create($data);
        if ($create_data) {
            $exist = $push_object->where(array('session_id' => session_id()))->find();
            if ($exist) {
                $result = $push_object->where(array('id' => $exist['id']))->save($create_data);
            } else {
                $create_data['session_id'] = session_id();
                $result = $push_object->add($create_data);
            }
            if ($result) {
                $this->success('推送Token上传成功');
            } else {
                $this->error('错误：' . $push_object->getError());
            }
        } else {
            $this->error('错误：' . $push_object->getError());
        }
    }
}
