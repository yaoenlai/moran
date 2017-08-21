<?php
// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------
namespace User\Controller;
use Home\Controller\HomeController;
use Common\Util\Think\Page;
/**
 * 帐号安全控制器
 * @author zxq
 */
class SafetyController extends HomeController {
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
     * @author zxq
     */
    public function index() {
        $uid = $this->is_login();
        $user_info = D('Admin/User')->find($uid);
        $this->assign('meta_title', '安全中心');
        $this->assign('user_info', $user_info);
        $this->display();
    }

    /**
     * 帐号绑定
     * @author zxq
     */
    public function bind() {
        if (IS_POST) {
            $uid = $this->is_login();
            $user_object = D('Admin/User');
            switch (I('post.bind_type')) {
                case 'email': //邮箱绑定
                    //验证码严格加盐加密验证
                    if (user_md5(I('post.verify'), I('post.email')) !== session('reg_verify')) {
                        $this->error('验证码错误！');
                    }

                    // 检查绑定
                    $map = array();
                    $map['email']      = I('post.email');
                    $map['email_bind'] = 1;
                    $exist = $user_object->where($map)->count();
                    if ($exist) {
                        $this->error('该邮箱已被绑定'. $user_object->getError());
                    }

                    // 开始绑定
                    $con = array('id' => $uid);
                    $result = $user_object->where($con)->setField('email', I('post.email'));
                    if ($result !== false) {
                        $status = $user_object->where($con)->setField('email_bind', 1);
                        if ($status !== false) {
                            // 构造消息数据
                            $msg_data['to_uid'] = $uid;
                            $msg_data['title']  = '绑定成功';
                            $msg_data['content'] = '您好：<br>'
                                                  .'恭喜您成功将邮箱'.I('post.email').'绑定了'.C('WEB_SITE_TITLE').'的帐号，'
                                                  .'您可以使用该邮箱直接登录'.C('WEB_SITE_TITLE').'。<br>'
                                                  .'<br>';
                            D('User/Message')->sendMessage($msg_data);
                            $this->success('恭喜您，邮箱绑定成功！', U('index'));
                        } else {
                            $this->error('邮箱绑定失败！'. $user_object->getError());
                        }
                    } else {
                        $this->error('邮箱绑定失败！'. $user_object->getError());
                    }
                    break;
                case 'mobile': //手机号绑定
                    //验证码严格加盐加密验证
                    if (user_md5(I('post.verify'), I('post.mobile')) !== session('reg_verify')) {
                        $this->error('验证码错误！');
                    }

                    // 检查绑定
                    $map = array();
                    $map['mobile']      = I('post.mobile');
                    $map['mobile_bind'] = 1;
                    $exist = $user_object->where($map)->count();
                    if ($exist) {
                        $this->error('该手机已被另一帐号绑定'. $user_object->getError());
                    }

                    // 开始绑定
                    $con = array('id' => $uid);
                    $result = $user_object->where($con)->setField('mobile', I('post.mobile'));
                    if ($result !== false) {
                        $status = $user_object->where($con)->setField('mobile_bind', 1);
                        if ($status !== false) {
                            // 构造消息数据
                            $msg_data['to_uid'] = $uid;
                            $msg_data['title']  = '绑定成功';
                            $msg_data['content'] = '您好：<br>'
                                                  .'恭喜您成功将手机号'.I('post.mobile').'绑定了'.C('WEB_SITE_TITLE').'的帐号，'
                                                  .'您可以使用该手机号直接登录'.C('WEB_SITE_TITLE').'。<br>'
                                                  .'<br>';
                            D('User/Message')->sendMessage($msg_data);
                            $this->success('恭喜您，手机绑定成功！', U('index'));
                        } else {
                            $this->error('手机绑定失败！'. $user_object->getError());
                        }
                    } else {
                        $this->error('手机绑定失败！'. $user_object->getError());
                    }
                    break;
            }
        } else {
            $this->assign('meta_title', '帐号绑定');
            $this->display();
        }
    }

    /**
     * 取消绑定
     * @author zxq
     */
    public function cancel() {
        $uid = $this->is_login();
        $user_object = D('Admin/User');
        switch (I('bind_type')) {
            case 'email':
                $con = array('id' => $uid);
                $result = $user_object->where($con)->setField('email', '');
                if ($result !== false) {
                    $status = $user_object->where($con)->setField('email_bind', 0);
                    if ($status !== false) {
                        $this->success('恭喜您，取消邮箱绑定成功！', U('index'));
                    } else {
                        $this->error('取消邮箱绑定失败！'. $user_object->getError());
                    }
                } else {
                    $this->error('邮箱绑定失败！'. $user_object->getError());
                }
                break;
            case 'mobile':
                $con = array('id' => $uid);
                $result = $user_object->where($con)->setField('mobile', '');
                if ($result !== false) {
                    $status = $user_object->where($con)->setField('mobile_bind', 0);
                    if ($status !== false) {
                        $this->success('恭喜您，取消手机绑定成功！', U('index'));
                    } else {
                        $this->error('取消手机绑定失败！'. $user_object->getError());
                    }
                } else {
                    $this->error('取消手机绑定失败！'. $user_object->getError());
                }
                break;
        }
    }
}
