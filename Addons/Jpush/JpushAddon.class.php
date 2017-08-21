<?php
// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------
namespace Addons\Jpush;
use Common\Controller\Addon;
/**
 * 极光推送插件
 * @author zxq
 */
class JpushAddon extends Addon {
    /**
     * 插件信息
     * @author zxq
     */
    public $info = array(
        'name'        => 'Jpush',
        'title'       => '极光推送插件',
        'description' => '实现极光推送功能',
        'status'      => 1,
        'author'      => 'Wiera',
        'version'     => '1.4.0',
    );

    /**
     * 插件安装方法
     * @author zxq
     */
    public function install() {
        return true;
    }

    /**
     * 插件卸载方法
     * @author zxq
     */
    public function uninstall() {
        return true;
    }
}
