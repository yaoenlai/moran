<?php
// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------
namespace Addons\RocketToTop;
use Common\Controller\Addon;
/**
 * 小火箭返回顶部
 * @zxq
 */
class RocketToTopAddon extends Addon{
    /**
     * 插件信息
     * @author zxq
     */
    public $info = array(
        'name'        => 'RocketToTop',
        'title'       => '小火箭返回顶部',
        'description' => '小火箭返回顶部',
        'status'      => '1',
        'author'      => 'Wiera',
        'version'     => '1.3.0',
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
        $addons_config = $this->getConfig();
        if($addons_config['status']){
            $this->display('rocket');
        }
    }
}
