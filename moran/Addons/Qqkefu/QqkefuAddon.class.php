<?php
// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------
namespace Addons\Qqkefu;
use Common\Controller\Addon;
/**
 * QQ客服
 * @zxq
 */
class QqkefuAddon extends Addon{
    /**
     * QQ客服
     * @author zxq
     */
    public $info = array(
        'name'        => 'Qqkefu',
        'title'       => 'QQ客服',
        'description' => 'QQ客服',
        'status'      => 1,
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
            $this->assign('addons_config', $addons_config);
            $this->display('index');
        }
    }
}
