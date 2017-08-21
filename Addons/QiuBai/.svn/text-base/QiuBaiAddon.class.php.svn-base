<?php
// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------
namespace Addons\QiuBai;
use Common\Controller\Addon;
/**
 * 系统环境信息插件
 * @author thinkphp
 */
class QiuBaiAddon extends Addon {
    /**
     * 插件信息
     * @author zxq
     */
    public $info = array(
        'name'        => 'QiuBai',
        'title'       => '糗事百科',
        'description' => '读别人的糗事，娱乐自己',
        'status'      => 1,
        'author'      => 'Wiera',
        'version'     => '1.3.0',
    );

    /**
     * 插件所需钩子
     * @author zxq
     */
    public $hooks = array(
        '0' => 'AdminIndex',
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
     * 实现的AdminIndex钩子方法
     * @author zxq
     */
    public function AdminIndex($param) {
        $config = $this->getConfig();
        $this->assign('addons_config', $config);
        if ($config['status']) {
            $this->display('widget');
        }
    }
}
