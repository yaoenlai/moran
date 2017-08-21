<?php
// +----------------------------------------------------------------------
// | OpenCMF [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.opencmf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
// 模块信息配置
return array (
  'info' => 
  array (
    'name' => 'Love',
    'title' => '交友',
    'icon' => 'fa fa-users',
    'icon_color' => '#F9B440',
    'description' => '交友中心模块',
    'developer' => '北京优异科技有限公司',
    'website' => 'http://www.uera.cn',
    'version' => '1.0.0',
    'dependences' => 
    array (
      'Admin' => '1.0.0',
    ),
  ),
  'user_nav' => 
  array (
    'title' => 
    array (
      'center' => '个人信息',
    ),
    'center' => 
    array (
      0 => 
      array (
        'title' => '修改信息',
        'icon' => 'fa fa-edit',
        'url' => 'Love/Center/profile',
        'color' => '#F68A3A',
      ),
      1 => 
      array (
        'title' => '对话聊天',
        'icon' => 'fa fa-commenting-o',
        'url' => 'Love/Talk/lists',
        'badge' => 
        array (
          0 => 'Love/Talk',
          1 => 'newTalkCount',
        ),
        'badge_class' => 'badge-danger',
        'color' => '#398CD2',
      ),
      2 => 
      array (
        'title' => '消息中心',
        'icon' => 'fa fa-envelope-o',
        'url' => 'Love/Message/index',
        'badge' => 
        array (
          0 => 'Love/Message',
          1 => 'newMessageCount',
        ),
        'badge_class' => 'badge-danger',
        'color' => '#80C243',
      ),
      3 => 
      array (
        'title' => '关注粉丝',
        'icon' => 'fa fa-users',
        'url' => 'Love/Follow/index',
        'badge' => 
        array (
          0 => 'Love/Follow',
          1 => 'newFansCount',
        ),
        'badge_class' => 'badge-danger',
        'color' => '#DC6AC6',
      ),
      4 => 
      array (
        'title' => '安全中心',
        'icon' => 'fa fa-shield',
        'url' => 'Love/Safety/index',
        'color' => '#3C9746',
      ),
    ),
    'main' => 
    array (
      0 => 
      array (
        'title' => '个人中心',
        'icon' => 'fa fa-tachometer',
        'url' => 'Love/Center/index',
      ),
    ),
  ),
  'config' => 
  array (
    'status' => 
    array (
      'title' => '是否开启',
      'type' => 'radio',
      'options' => 
      array (
        1 => '开启',
        0 => '关闭',
      ),
      'value' => '1',
    ),
    'paramter_ver' => 
    array (
      'title' => '预设配置版本',
      'type' => 'text',
      'value' => '1',
    ),
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
    'deny_username' => 
    array (
      'title' => '禁止注册的用户名',
      'type' => 'textarea',
      'value' => '',
    ),
    'protocol' => 
    array (
      'title' => '服务协议',
      'type' => 'textarea',
      'value' => '请在“后台——交友——交友设置”中设置',
    ),
    'behavior' => 
    array (
      'title' => '行为扩展',
      'type' => 'checkbox',
      'options' => 
      array (
        'Love' => 'Love',
      ),
      'value' => 
      array (
        0 => 'User',
      ),
    ),
  ),
  'admin_menu' => 
  array (
    1 =>
    array (
      'pid' => '0',
      'title' => '交友',
      'icon' => 'fa fa-user',
      'id' => 1,
    ),
    2 =>
    array (
      'pid' => '1',
      'title' => '配置中心',
      'icon' => 'fa fa-folder-open-o',
      'id' => 2,
    ),
    3 =>
      array (
        'pid' => '1',
        'title' => '用户管理',
        'icon' => 'fa fa-folder-open-o',
        'id' => 3,
    ),
    4 =>
      array (
        'pid' => '1',
        'title' => '用户记录',
        'icon' => 'fa fa-folder-open-o',
        'id' => 4,
    ),
    5 =>
    array (
      'id' => 5,
      'pid' => '2',
      'title' => '系统配置',
      'url' => 'Love/Index/module_config',
      'icon' => 'fa fa-wrench',
    ),
    6 =>
    array (
      'id' => 6,
      'pid' => '2',
      'title' => '终端管理',
      'url' => 'Love/Site/index',
      'icon' => 'fa fa-folder-open-o',
    ),
    7 =>
    array (
      'pid' => '6',
      'title' => '新增',
      'url' => 'Love/Site/add',
      'icon' => 'fa-plus-circle',
      'id' => 7,
    ),
    8 =>
    array (
      'pid' => '6',
      'title' => '编辑',
      'url' => 'Love/Site/edit',
      'icon' => 'fa-text-width',
      'id' => 8,
    ),
    9 =>
    array (
      'pid' => '6',
      'title' => '设置状态',
      'url' => 'Love/Site/setStatus',
      'icon' => 'fa-cog',
    ),
    10 =>
    array (
      'id' => 10,
      'pid' => '2',
      'title' => '预设参数',
      'url' => 'Love/Paramter/index',
      'icon' => 'fa fa-list-alt',
    ),
    11 =>
    array (
      'pid' => '10',
      'title' => '新增',
      'url' => 'Love/Paramter/add',
      'id' => 11,
    ),
    12 =>
    array (
      'pid' => '10',
      'title' => '编辑',
      'url' => 'Love/Paramter/edit',
      'id' => 12,
    ),
    13 =>
    array (
      'pid' => '10',
      'title' => '设置状态',
      'url' => 'Love/Paramter/setStatus',
      'id' => 13,
    ),
    14 =>
    array (
      'pid' => '2',
      'title' => '招呼管理',
      'url' => 'Love/Greet/index',
      'icon' => 'fa fa-comment',
      'id' => 14,
    ),
    15 =>
    array (
      'pid' => '14',
      'title' => '新增',
      'url' => 'Love/Greet/add',
      'icon' => '',
      'id' => 15,
    ),
    16 =>
      array (
          'pid' => '14',
          'title' => '编辑',
          'url' => 'Love/Greet/edit',
          'icon' => '',
          'id' => 16,
    ),
    17 =>
      array (
          'pid' => '14',
          'title' => '设置状态',
          'url' => 'Love/Greet/status',
          'icon' => '',
          'id' => 17,
    ),
    18 =>
    array (
      'pid' => '3',
      'title' => '用户统计',
      'icon' => 'fa fa-area-chart',
      'url' => 'Love/Index/index',
      'id' => 18,
    ),
    19 =>
    array (
      'pid' => '3',
      'title' => '用户列表',
      'icon' => 'fa fa-list',
      'url' => 'Love/User/index',
      'id' => 19,
    ),
    20 =>
    array (
      'pid' => '19',
      'title' => '新增',
      'url' => 'Love/Love/add',
      'id' => 6,
    ),
    21 =>
    array (
      'pid' => '19',
      'title' => '编辑',
      'url' => 'Love/Love/edit',
      'id' => 7,
    ),
    22 =>
    array (
      'pid' => '19',
      'title' => '设置状态',
      'url' => 'Love/Love/setStatus',
      'id' => 8,
    ),
    23 =>
    array (
      'pid' => '3',
      'title' => '用户类型',
      'icon' => 'fa fa-user',
      'url' => 'Love/Type/index',
      'id' => 23,
    ),
    24 =>
    array (
      'pid' => '23',
      'title' => '新增',
      'url' => 'Love/Type/add',
      'id' => 24,
    ),
    25 =>
    array (
      'pid' => '23',
      'title' => '编辑',
      'url' => 'Love/Type/edit',
      'id' => 25,
    ),
    26 =>
    array (
      'pid' => '23',
      'title' => '设置状态',
      'url' => 'Love/Type/setStatus',
      'id' => 26,
    ),
    27 =>
    array (
      'pid' => '3',
      'title' => '消息管理',
      'icon' => 'fa fa-envelope-o',
      'url' => 'Love/Message/index',
      'id' => 27,
    ),
    28 =>
    array (
      'pid' => '27',
      'title' => '新增',
      'url' => 'Love/Message/add',
      'id' => 28,
    ),
    29 =>
    array (
      'pid' => '27',
      'title' => '编辑',
      'url' => 'Love/Message/edit',
      'id' => 29,
    ),
    30 =>
    array (
      'pid' => '27',
      'title' => '设置状态',
      'url' => 'Love/Message/setStatus',
      'id' => 30,
    ),
    31 =>
    array (
      'id' => 31,
      'pid' => '4',
      'title' => '消费纪录',
      'url' => 'Love/Order/index',
      'icon' => 'fa fa-usd',
    ),
    32 =>
    array (
      'id' => 32,
      'pid' => '4',
      'title' => '积分纪录',
      'url' => 'Love/Log/score',
      'icon' => 'fa fa-genderless',
    ),
    33 =>
    array (
      'id' => 33,
      'pid' => '4',
      'title' => '登录日志',
      'url' => 'Love/Log/login',
      'icon' => 'fa fa-calendar-check-o',
    ),
    34 =>
    array (
      'pid' => '19',
      'title' => '资料',
      'url' => 'Love/Profile/index',
      'id' => '34',
    ),
    35 =>
    array (
      'pid' => '19',
      'title' => '隐私',
      'url' => 'Love/Attr/index',
      'id' => '35',
    ),
    36 =>
    array (
      'pid' => '19',
      'title' => '择友',
      'url' => 'Love/Cond/index',
      'id' => '36',
    ),
    37 =>
    array (
      'pid' => '19',
      'title' => '新增',
      'url' => 'Love/User/add',
      'id' => '37',
    ),
    38 =>
    array (
      'pid' => '19',
      'title' => '编辑',
      'url' => 'Love/User/edit',
      'id' => '38',
    ),
  ),
)
;