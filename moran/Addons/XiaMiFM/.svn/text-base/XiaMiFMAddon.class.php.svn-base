<?php
// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------
namespace Addons\XiaMiFM;
use Common\Controller\Addon;
/**
 * 虾米音乐电台
 * @author Moobusy
 */
class XiaMiFMAddon extends Addon{
    /**
     * 插件信息
     * @author zxq
     */
    public $info = array(
        'name'        => 'XiaMiFM',
        'title'       => '虾米音乐电台',
        'description' => '虾米音乐电台',
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
    public function AdminIndex($param){
        $config = $this->getConfig();
        if ($config['onuse']==1) {
            $this->display('index');
        }
    }
}
