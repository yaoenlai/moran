<?php
// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------
namespace Addons\Demo;
use Common\Controller\Addon;
/**
 * 演示插件
 * @author thinkphp
 */
class DemoAddon extends Addon {
    /**
     * 插件信息
     * @author zxq
     */
    public $info = array(
        'name'        => 'Demo',
        'title'       => '演示插件',
        'description' => '演示插件',
        'status'      => 1,
        'author'      => 'Wiera',
        'version'     => '1.3.0',
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
        if($config['status']){
            $demo = '<div class="alert alert-danger text-center" style="margin:0;"><i class="fa fa-bullhorn"></i> 重要：您当前访问的是演示站点，不提供任何数据服务！！！</div>';
            echo $demo;
        }
    }
}
