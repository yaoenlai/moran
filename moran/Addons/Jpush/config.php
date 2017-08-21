<?php
// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------

return array(
    'status' => array(
        'title' => '是否开启极光推送:',
        'type'  => 'radio',
        'options' => array(
            '1' => '开启',
            '0' => '关闭',
        ),
        'value' => '1',
    ),
    'app_key' => array(
        'title' => 'app_key:',
        'type'  =>'text',
        'value' => '',
    ),
    'master_secret' =>array(
        'title' => 'master_secret:',
        'type'  =>'text',
        'value' => '',
    ),
    'production' =>array(
        'title' => 'APNS发送模式:',
        'type'  =>'radio',
        'options' => array(
            '1' => '生产模式',
            '0' => '开发模式',
        ),
        'value' => '',
    ),
);
