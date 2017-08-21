<?php
// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------
namespace Weixin\Behavior;
use Think\Behavior;
use Think\Hook;
defined('THINK_PATH') or exit();
/**
 * 微信下自动登录
 * @author zxq
 */
class WeixinBehavior extends Behavior {
    /**
     * 行为扩展的执行入口必须是run
     * @author zxq
     */
    public function run(&$content) {
        // 判断判断是微信浏览器自动跳转登录页面
        if (\Common\Util\Device::isWeixin() && !(is_login()) && C('weixin_config.weixin_login') === '1' && CONTROLLER_NAME !== 'UserBind') {
            $url = U('Weixin/UserBind/index', null, true, true);
            header("Location: $url");
        }
    }
}
