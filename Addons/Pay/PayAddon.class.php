<?php
// +----------------------------------------------------------------------
// | OpenCMF [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.opencmf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace Addons\Pay;
use Common\Controller\Addon;
/**
 * 支付插件
 * @author jry <598821125@qq.com>
 */
class PayAddon extends Addon {
    /**
     * 插件信息
     * @author jry <598821125@qq.com>
     */
    public $info = array(
        'name'        => 'Pay',
        'title'       => '支付插件',
        'description' => '支付插件，支持支付宝、微信、银联、财付通、Paypal、快钱、易宝',
        'status'      => 1,
        'author'      => 'OpenCMF',
        'version'     => '1.4.0',
    );

    /**
     * 插件安装方法
     * @author jry <598821125@qq.com>
     */
    public function install(){
        return true;
    }

    /**
     * 插件卸载方法
     * @author jry <598821125@qq.com>
     */
    public function uninstall(){
        return true;
    }
}
