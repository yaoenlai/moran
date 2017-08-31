<?php
// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------

/**
 * CoreThink数据库连接配置文件
 */

// 开启开发部署模式
if (@$_SERVER[ENV_PRE.'DEV_MODE'] === 'true') {
    // 数据库配置
    return array(
        'DB_TYPE'   => $_SERVER[ENV_PRE.'DB_TYPE'] ? : 'mysql',       // 数据库类型
        'DB_HOST'   => $_SERVER[ENV_PRE.'DB_HOST'] ? : '127.0.0.1',       // 服务器地址 
        'DB_NAME'   => $_SERVER[ENV_PRE.'DB_NAME'] ? : 'ue_love',       // 数据库名
        'DB_USER'   => $_SERVER[ENV_PRE.'DB_USER'] ? : 'root',       // 用户名
        'DB_PWD'    => $_SERVER[ENV_PRE.'DB_PWD']  ? : '',        // 密码
        'DB_PORT'   => $_SERVER[ENV_PRE.'DB_PORT'] ? : '3306',            // 端口
        'DB_PREFIX' => $_SERVER[ENV_PRE.'DB_PREFIX'] ? : 'ue_',   // 数据库表前缀
    );
} else {
    // 数据库配置
    return array(
        'DB_TYPE'   => 'mysql',       // 数据库类型
        'DB_HOST'   => '127.0.0.1',       // 服务器地址
        'DB_NAME'   => 'ue_love',       // 数据库名
        'DB_USER'   => 'root',       // 用户名
        'DB_PWD'    => '',        // 密码
        'DB_PORT'   => '3306',            // 端口
        'DB_PREFIX' => 'ue_',     // 数据库表前缀
    );
}
