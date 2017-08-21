<?php
// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------
namespace Addons\Weather;
use Common\Controller\Addon;
/**
 * 天气插件
 * @author cepljxiongjun
 */
class WeatherAddon extends Addon {
    /**
     * 插件信息
     * @author zxq
     */
    public $info = array(
        'name'        => 'Weather',
        'title'       => '天气预报',
        'description' => '天气预报',
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
    public function AdminIndex(){
        $config = $this->getConfig();
        $this->assign('addons_config', $config);
        if($config['status']) {
            $this->display('widget');
        }
    }
}
