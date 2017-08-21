<?php
// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------
namespace Addons\SyncLogin;
use Common\Controller\Addon;
/**
 * 同步登陆插件
 * @author zxq
 */
class SyncLoginAddon extends Addon{
    /**
     * 插件信息
     * @author zxq
     */
    public $info = array(
        'name'        => 'SyncLogin',
        'title'       => '第三方账号登陆',
        'description' => '第三方账号登陆',
        'status'      => 1,
        'author'      => 'Wiera',
        'version'     => '1.4.0',
    );

    /**
     * 插件所需钩子
     * @author zxq
     */
    public $hooks = array(
        '0' => 'SyncLogin',
    );

    /**
     * 自定义插件后台
     * @author zxq
     */
    //public $custom_adminlist = './Addons/SyncLogin/admin.html';

    /**
     * 插件后台数据表配置
     * @author zxq
     */
    public $admin_list = array(
        '1' => array(
            'title' => '第三方登录列表',
            'model' => 'sync_login',
        )
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
     * 登录按钮钩子
     * @author zxq
     */
    public function SyncLogin($param){
        $this->assign($param);
        $config = $this->getConfig();
        $this->assign('config',$config);
        $this->display('login');
    }

    /**
     * meta代码钩子
     * @author zxq
     */
    public function PageHeader($param){
        $platform_options = $this->getConfig();
        echo $platform_options['meta'];
    }
}
