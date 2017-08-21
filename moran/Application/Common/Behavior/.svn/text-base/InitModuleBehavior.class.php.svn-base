<?php
// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------
namespace Common\Behavior;
use Think\Behavior;
defined('THINK_PATH') or exit();
/**
 * 初始化允许访问模块信息
 * @author zxq
 */
class InitModuleBehavior extends Behavior{
    /**
     * 行为扩展的执行入口必须是run
     * @author zxq
     */
    public function run(&$content) {
        // 安装模式下直接返回
        if(defined('BIND_MODULE') && BIND_MODULE === 'Install') return;

        // 数据缓存前缀
        $config['DATA_CACHE_PREFIX'] = strtolower(ENV_PRE.MODULE_MARK.'_');

        // URL_MODEL必须在app_init阶段就从数据库读取出来应用
        // 不然系统就会读取config.php中的配置导致后台的配置失效
        $config['URL_MODEL'] = D('Admin/Config')->getFieldByName('URL_MODEL', 'value');

        // 允许访问模块列表加上安装的功能模块
        $module_name_list  = D('Admin/Module')
                           ->where(array('status' => 1, 'is_system' => 0))
                           ->getField('name', true);
        $module_allow_list = array_merge(
            C('MODULE_ALLOW_LIST'),
            $module_name_list
        );
        if (MODULE_MARK === 'Admin') {
            $module_allow_list[] = 'Admin';
            $config['URL_MODEL'] = 3;
        }
        C('MODULE_ALLOW_LIST', $module_allow_list);

        // 如果是后台访问自动设置默认模块为Admin
        if (MODULE_MARK === 'Admin') {
            C('DEFAULT_MODULE', 'Admin');
        }

        // 根据URL参数设置默认模块
        if ($_GET['default_module']) {
            C('DEFAULT_MODULE', $_GET['default_module']);
        }

        // API专用接口
        $config['AJAX_API_DOMAIN'] = D('Admin/Config')->getFieldByName('AJAX_API_DOMAIN', 'value');
        if ($config['AJAX_API_DOMAIN'] === $_SERVER['HTTP_HOST'] && MODULE_MARK === 'Home') {
            $_SERVER['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';
            C('IS_API', true);
            header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);
            header('Access-Control-Allow-Headers: X-Requested-With');
            header("Access-Control-Allow-Credentials: true");
        }

        C($config);
    }
}
