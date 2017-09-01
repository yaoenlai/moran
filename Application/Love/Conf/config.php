<?php
// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------
return array(
    // 路由配置
    'URL_ROUTER_ON'     => true,
    'URL_MAP_RULES'     => array(
    ),
    'URL_ROUTE_RULES'   => array(
        ':uid\d'         => 'index/home',
    ),
    // 套餐先粗糙配置，后期可以入库缓存
    'package' => [
        119 => [
            'package_name' => '159元/全年',
            'alias_name' => '套餐A 全年VIP会员',
            'money' => 159,
            'icon' => 'price_1@2x.png',
            'default_selected' => true,
            'isrecommend' => true,
            'gift' => 150,
            'payid' => 119,
            'level' => 5,
            'time' => 31536000,// 秒级
        ],
        118 => [
            'package_name' => '129元/半年',
            'alias_name' => '套餐B 半年VIP会员',
            'money' => 129,
            'icon' => 'price_2@2x.png',
            'default_selected' => false,
            'isrecommend' => false,
            'gift' => 100,
            'payid' => 118,
            'level' => 4,
            'time' => 15768000,// 秒级
        ],
        117 => [
            'package_name' => '99元/90天',
            'alias_name' => '套餐C 90天VIP会员',
            'money' => 99,
            'icon' => 'price_3@2x.png',
            'default_selected' => false,
            'isrecommend' => false,
            'gift' => 50,
            'payid' => 117,
            'level' => 3,
            'time' => 7776000,// 秒级
        ],
        116 => [
            'package_name' => '69元/30天',
            'alias_name' => '套餐D 30天VIP会员',
            'money' => 69,
            'icon' => 'price_4@2x.png',
            'default_selected' => false,
            'isrecommend' => false,
            'gift' => '',
            'payid' => 116,
            'level' => 2,
            'time' => 2592000,// 秒级
        ],
        115 => [
            'package_name' => '49元/7天',
            'alias_name' => '套餐D 7天VIP会员',
            'money' => 49,
            'icon' => 'price_5@2x.png',
            'default_selected' => false,
            'isrecommend' => false,
            'gift' => '',
            'payid' => 115,
            'level' => 1,
            'time' => 604800,// 秒级
        ]
    ]
);
