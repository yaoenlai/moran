<?php
// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------
return array(
    'status'=>array(
        'title'=>'是否开启短信:',
        'type'=>'radio',
        'options'=>array(
            '1'=>'开启',
            '0'=>'关闭',
        ),
        'value'=>'1',
    ),
    'appkey'=>array(
        'title'=>'APPKEY：:',
        'type'=>'text',
        'value'=>'',
        'tip'=>'请通过www.alidayu.com申请',
    ),
    'secret'=>array(
        'title' => 'SECRET：:',
        'type'  => 'text',
        'value' => '',
        'tip'=>'请通过www.alidayu.com申请',
    ),
    'sign_name'=>array(
        'title' => '短信签名',
        'type'  => 'text',
        'value' => '',
        'tip'=>'必须存在于http://www.alidayu.com/admin/service/sign列表里',
    ),
    'template_code'=>array(
        'title' => '短信模版',
        'type'  => 'text',
        'value' => '',
        'tip'=>'必须存在于http://www.alidayu.com/admin/service/tpl列表里',
    ),
);
