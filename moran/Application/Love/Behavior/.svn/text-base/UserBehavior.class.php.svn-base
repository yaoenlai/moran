<?php
// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------
namespace Love\Behavior;
use Think\Behavior;
use Think\Hook;
defined('THINK_PATH') or exit();
/**
 * 用户消息
 * @author zxq
 */
class UserBehavior extends Behavior {
    /**
     * 行为扩展的执行入口必须是run
     * @author zxq
     */
    public function run(&$content) {
        $uid = is_login();
        if ($uid) {
            // 获取用户未读消息数量
            $_new_message = D('User/Message')->newMessageCount() + D('User/Talk')->newTalkCount();
            cookie('_new_message', $_new_message ? : null, array('path' => __ROOT__));

            // 更新session用户信息
            if((time()-session('user_auth_expire')) > 60){
                $user_info = D('Admin/User')->getUserInfo($uid);
                if(D('User/User')->auto_login($user_info)) {
                    session('user_auth_expire', time());
                }
            }
        }
    }
}
