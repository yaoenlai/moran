<?php
// +----------------------------------------------------------------------
// | OpenCMF [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.opencmf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
//模块信息配置
return array(
    //模块信息
    'info' => array(
        'name'        => 'Recharge',
        'title'       => '充值',
        'icon'        => 'fa fa-money',
        'icon_color'  => '#F9B440',
        'description' => '用户充值模块',
        'developer'   => '北京优异科技有限公司',
        'website'     => 'http://www.uera.cn',
        'version'     => '1.2.0',
        'dependences' => array(
            'Admin'   => '1.1.0',
            'User'    => '1.1.0',
        )
    ),
    
  'config' => 
  array (
    'domain_all' => 
    array (
      'title' => '模块所有域名绑定',
      'type' => 'array',
      'value' => "1:不带http的域名1",
    ),
    'domain_api' => 
    array (
      'title' => '模块接口域名绑定',
      'type' => 'text',
      'value' => "带http的域名,该域名必须已经绑定模块",
    ),
  ),
    //后台菜单及权限节点配置
    'admin_menu' => array(
        '1' => array(
            'id'    => '1',
            'pid'   => '0',
            'title' => '充值',
            'icon'  => 'fa fa-money',
            //'top'   => 'User',
        ),
        '2' => array(
            'pid'   => '1',
            'title' => '充值管理',
            'icon'  => 'fa fa-folder-open-o',
        ),
	    3 => 
	    array (
	      'pid' => '2',
	      'title' => '模块配置',
	      'icon' => 'fa fa-wrench',
	      'url' => 'Recharge/Index/module_config',
	      'id' => 3,
	    ),
        '4' => array(
            'pid'   => '2',
            'title' => '充值记录表',
            'icon'  => 'fa fa-money',
            'url'   => 'Recharge/Index/index',
        ),
    )
);
