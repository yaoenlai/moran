<?php
// +----------------------------------------------------------------------
// | OpenCMF [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.opencmf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace Addons\Pay\Model;
use Think\Model;
use Home\Controller\AddonController;
/**
 * 支付插件控制器
 * @author jry <598821125@qq.com>
 */
class PayModel {
    /**
     * 获取支付方式列表
     * @author jry <598821125@qq.com>
     */
    function type_list($type) {
        $addon_config = \Common\Controller\Addon::getConfig('Pay');
        if ($addon_config['status']) {
            foreach ($addon_config['allow_pay_type'] as &$val) {
                $val1['type'] = $val;
                if (C('STATIC_DOMAIN')) {
                    $val1['logo'] = C('STATIC_DOMAIN') . '/Addons/Pay/logo/' . $val . '.jpg';
                } else {
                    $val1['logo'] = C('HOME_PAGE') . '/Addons/Pay/logo/' . $val . '.jpg';
                }
                $val = $val1;
            }
            if ($type) {
                return $addon_config['allow_pay_type']['type'];
            } else {
                return $addon_config['allow_pay_type'];
            }
        } else {
            return false;
        }
    }

    /**
     * 获取支付配置
     * @author jry <598821125@qq.com>
     */
    function pay_config($type) {
        $addon_config = \Common\Controller\Addon::getConfig('Pay');
        if ($addon_config['status']) {
			if($type == 'aliwappay')
				$type = 'alipay';
            if ($type === 'wxpay') {
                if (C('IS_API')) {
                    $pay_config = array();
                    $type = 'wxapppay';
                    $pay_config['appid']     = $addon_config[$type.'_appid'];
                    $pay_config['appsecret'] = $addon_config[$type.'_appsecret'];
                    $pay_config['mchid']     = $addon_config[$type.'_mchid'];
                    $pay_config['key']       = $addon_config[$type.'_key'];
                } else {
                    $pay_config = array();
                    $pay_config['appid']     = $addon_config[$type.'_appid'];
                    $pay_config['appsecret'] = $addon_config[$type.'_appsecret'];
                    $pay_config['mchid']     = $addon_config[$type.'_mchid'];
                    $pay_config['key']       = $addon_config[$type.'_key'];
                }
			} elseif($type === 'nowpay') {
				//对现在支付特殊处理
				$pay_config = array();
				$pay_config['appid']      = $addon_config[$type.'_appid'];
                $pay_config['key']        = $addon_config[$type.'_key'];
				
				$pay_config['wap_appid']      = $addon_config[$type.'_wap_appid'];
                $pay_config['wap_key']        = $addon_config[$type.'_wap_key'];
				
				$pay_config['app_appid']      = $addon_config[$type.'_app_appid'];
                $pay_config['app_key']        = $addon_config[$type.'_app_key'];
			} else {
                $pay_config = array();
				$pay_config['appid']      = $addon_config[$type.'_appid'];
                $pay_config['email']      = $addon_config[$type.'_email'];
                $pay_config['partner']    = $addon_config[$type.'_partner'];
                $pay_config['key']        = $addon_config[$type.'_key'];
                $pay_config['business']   = $addon_config[$type.'_business'];
                if ($type === 'alipay') {
                    $pay_config['private_key']   = $addon_config[$type.'_private_key'];
                    $pay_config['ali_public_key']   = $addon_config[$type.'_ali_public_key'];
                }
            }
            return $pay_config;
        } else {
            return false;
        }
    }
}
