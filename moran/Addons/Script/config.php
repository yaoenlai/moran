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
        'title'=>'是否开启:',
        'type'=>'radio',
        'options'=>array(
            '1'=>'开启',
            '0'=>'关闭',
        ),
        'value'=>'1',
    ),
    'script'=>array(
        'title'=>'js代码:',
        'type'=>'textarea',
        'value'=>''
    ),
    'deny'=>array(
        'title'=>'屏蔽地址列表',
        'type'=>'array',
        'value'=>''
    )
);
