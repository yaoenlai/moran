<?php
// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------
namespace Addons\BaiduShare;
use Common\Controller\Addon;
/**
 * 百度分享插件
 * @author jry
 */
class BaiduShareAddon extends Addon {
    /**
     * 插件信息
     * @author zxq
     */
    public $info = array(
        'name'        => 'BaiduShare',
        'title'       => '百度分享',
        'description' => '用户将网站内容分享到第三方网站',
        'status'      => 1,
        'author'      => 'Wiera',
        'version'     => '1.3.0',
    );

    /**
     * 插件所需钩子
     * @author zxq
     */
    public $hooks = array(
        '0' => 'BaiduShare',
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
     * 实现的BaiduShare钩子方法
     * @author zxq
     */
    public function BaiduShare($param){
        $this->assign('info', $param);
        $this->assign('addons_config', $this->getConfig());
        $this->display('share');
    }
}
