<?php
// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------
namespace Addons\Frog;
use Common\Controller\Addon;
/**
 * 青蛙插件
 * @author tomato
 */
class FrogAddon extends Addon{
    /**
     * 插件信息
     * @author zxq
     */
    public $info = array(
        'name'        => 'Frog',
        'title'       => '青蛙挂件',
        'description' => '有什么能帮您的么？',
        'status'      => 1,
        'author'      => 'Wiera',
        'version'     => '1.3.0',
    );

    /**
     * 插件所需钩子
     * @author zxq
     */
    public $hooks = array(
        '0' => 'PageFooter',
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
     * 实现的PageFooter钩子方法
     * @author zxq
     */
    public function PageFooter($param){
        $config = $this->getConfig();
        $this->assign('config', $config);
        if ($config['status']) {
            $this->display('index');
        }
    }
}
