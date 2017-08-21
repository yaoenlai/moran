<?php
// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------
namespace Addons\Safety;
use Common\Controller\Addon;
/**
 * 帐号安全提示插件
 * @author thinkphp
 */
class SafetyAddon extends Addon {
    /**
     * 插件信息
     * @author zxq
     */
    public $info = array(
        'name'        => 'Safety',
        'title'       => '帐号安全提示插件',
        'description' => '帐号安全提示插件',
        'status'      => 1,
        'author'      => 'Wiera',
        'version'     => '1.4.0',
    );

    /**
     * 插件所需钩子
     * @author zxq
     */
    public $hooks = array(
        '0' => 'PageHeader',
    );

    /**
     * 插件安装方法
     * @author zxq
     */
    public function install(){
        return true;
    }

    /**
     * 插件卸载方法
     * @author zxq
     */
    public function uninstall(){
        return true;
    }

    /**
     * 实现的PageHeader钩子方法
     * @author zxq
     */
    public function PageHeader($param){
        //检查插件是否开启
        $config = $this->getConfig();
        $uid = is_login();
        if ($uid) {
            $user_info = D('Admin/User')->getUserInfo($uid);
            $current_url = MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;
            if($config['status'] && $config['email'] && !$user_info['email_bind']){
                if ($config['force_email'] && 'User/Safety/bind' !== $current_url) {
                    redirect(U('User/Safety/bind'), 2, '为了您的账户安全，请先绑定邮箱，页面跳转中...');
                } else {
                    $demo = '<div class="alert alert-dark alert-full text-center" style="margin:0;"><i class="fa fa-bullhorn"></i> 重要：您尚未绑定邮箱账号，<a href="'.U('User/Safety/index').'">点击绑定</a>！！！</div>';
                    echo $demo;
                }
            }
            if($config['status'] && $config['mobile'] && !$user_info['mobile_bind']){
                if ($config['force_mobile'] && 'User/Safety/bind' !== $current_url) {
                    redirect(U('User/Safety/bind'), 2, '为了您的账户安全，请先绑定手机号，页面跳转中...');
                } else {
                    $demo = '<div class="alert alert-dark alert-full text-center" style="margin:0;"><i class="fa fa-bullhorn"></i> 重要：您尚未绑定手机账号，<a href="'.U('User/Safety/index').'">点击绑定</a>！！！</div>';
                    echo $demo;
                }
            }
        }
    }
}
