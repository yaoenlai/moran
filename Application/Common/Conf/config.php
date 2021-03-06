<?php
// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------

/**
 * Wiera全局配置文件
 */
$_config = array(
    /**
     * 产品配置
     * 根据Wiera用户协议：
     * 任何情况下使用Wiera均需获取官方授权，违者追究法律责任，授权联系：xq@uera.cn
     */
    'SECRET_KEY' => '21353f258c37757b280a12b806cc0ff6d21fb87e', //程序内部所用秘钥
    'PRODUCT_NAME' => 'Wiera', // 产品名称
    'PRODUCT_LOGO' => '<b><span style="color: #a5aeb4;">Wi</span><span style="color: #3fa9f5;">era</span></b>', // 产品Logo
    'CURRENT_VERSION' => '1.4.0', // 当前版本号
    'DEVELOP_VERSION' => 'beta3', // 开发版本号
    'BUILD_VERSION' => '201606011930', // 编译标记
    'PRODUCT_MODEL' => 'professional', // 产品型号
    'PRODUCT_TITLE' => '专业版', // 产品标题
    'WEBSITE_DOMAIN' => 'http://www.momoran.cn', // 官方网址
    'UPDATE_URL' => '/appstore/home/core/update', // 官方更新网址
    'COMPANY_NAME' => '北京陌然科技有限公司', // 公司名称
    'DEVELOP_TEAM' => '北京陌然科技有限公司', // 当前项目开发团队名称
    // 产品简介
    'PRODUCT_INFO' => 'Wiera是一套基于统一核心的通用互联网+信息化服务解决方案，追求简单、高效、卓越。可轻松实现支持多终端的WEB产品快速搭建、部署、上线。系统功能采用模块化、组件化、插件化等开放化低耦合设计，应用商城拥有丰富的功能模块、插件、主题，便于用户灵活扩展和二次开发。',
    // 公司简介
    'COMPANY_INFO' => '北京优异科技有限公司是一家新兴的互联网+项目技术解决方案提供商。我们用敏锐的视角洞察IT市场的每一次变革,我们顶着时代变迁的浪潮站在了前沿,以开拓互联网行业新渠道为己任。',
    // 系统主页地址配置
    'HOME_DOMAIN' => (is_ssl() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'],
    'HOME_PAGE' => (is_ssl() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . __ROOT__,
    // URL模式
    'URL_MODEL' => '3',
    // Session支持
//    'SESSION_OPTIONS'=>array(
//        'expire' => 8640000,
//        'type'   => 'Db',
//        'domain' => strstr($_SERVER['HTTP_HOST'], '.'),//session作用域至顶域
//    ),
  
    //Redis Session配置
    'SESSION_AUTO_START' => true, // 是否自动开启Session
    'SESSION_TYPE' => 'Redis', //session类型
    'SESSION_PERSISTENT' => 1,//是否长连接(对于php来说0和1都一样)
    'SESSION_CACHE_TIME'=> 30, //连接超时时间(秒)
    'SESSION_EXPIRE'=> 7200, //session有效期(单位:秒) 0表示永久缓存
    'SESSION_PREFIX'=> 'sess_', //session前缀
    'SESSION_REDIS_HOST' => '47.94.80.152', //分布式Redis,默认第一个为主服务器
    'SESSION_REDIS_PORT'=> '16379', //端口,如果相同只填一个,用英文逗号分隔
    'SESSION_REDIS_AUTH' => 'junglecat@~127.0', //Redis auth认证(密钥中不能有逗号),如果相同只填一个,用英文逗号分隔
    
    
    // 全局过滤配置
    'DEFAULT_FILTER' => '', //TP默认为htmlspecialchars
    // 预先加载的标签库
    'TAGLIB_PRE_LOAD' => 'Home\\TagLib\\Opencmf',
    // URL配置
    'URL_CASE_INSENSITIVE' => true, // 不区分大小写
    // 应用配置
    'DEFAULT_MODULE' => 'Home',
    'MODULE_DENY_LIST' => array('Common'),
    'MODULE_ALLOW_LIST' => array('Home', 'Install', "Yyadmin"),
    // 模板相关配置
    'TMPL_PARSE_STRING' => array(
        '__PUBLIC__' => __ROOT__ . '/Public',
        '__CUI__' => __ROOT__ . '/Public/libs/cui/dist',
        '__ADMIN_IMG__' => __ROOT__ . '/' . APP_PATH . 'Admin/View/Public/img',
        '__ADMIN_CSS__' => __ROOT__ . '/' . APP_PATH . 'Admin/View/Public/css',
        '__ADMIN_JS__' => __ROOT__ . '/' . APP_PATH . 'Admin/View/Public/js',
        '__ADMIN_LIBS__' => __ROOT__ . '/' . APP_PATH . 'Admin/View/Public/libs',
        '__HOME_IMG__' => __ROOT__ . '/' . APP_PATH . 'Home/View/Public/img',
        '__HOME_CSS__' => __ROOT__ . '/' . APP_PATH . 'Home/View/Public/css',
        '__HOME_JS__' => __ROOT__ . '/' . APP_PATH . 'Home/View/Public/js',
        '__HOME_LIBS__' => __ROOT__ . '/' . APP_PATH . 'Home/View/Public/libs',
    ),
    // 系统功能模板
    'USER_CENTER_SIDE' => APP_PATH . 'User/View/Index/side.html',
    'USER_CENTER_FORM' => APP_PATH . 'User/View/Builder/form.html',
    'USER_CENTER_LIST' => APP_PATH . 'User/View/Builder/list.html',
    'HOME_PUBLIC_LAYOUT' => APP_PATH . 'Home/View/Public/layout.html',
    'ADMIN_PUBLIC_LAYOUT' => APP_PATH . 'Admin/View/Public/layout.html',
    'HOME_PUBLIC_MODAL' => APP_PATH . 'Home/View/Public/modal.html',
    'LISTBUILDER_LAYOUT' => APP_PATH . 'Common/Builder/listbuilder.html',
    'FORMBUILDER_LAYOUT' => APP_PATH . 'Common/Builder/formbuilder.html',
    // 错误页面模板
    'TMPL_ACTION_ERROR' => APP_PATH . 'Home/View/Public/think/error.html', // 错误跳转对应的模板文件
    'TMPL_ACTION_SUCCESS' => APP_PATH . 'Home/View/Public/think/success.html', // 成功跳转对应的模板文件
    'TMPL_EXCEPTION_FILE' => APP_PATH . 'Home/View/Public/think/exception.html', // 异常页面的模板文件
    // 文件上传默认驱动
    'UPLOAD_DRIVER' => 'Local',
    // 文件上传相关配置
    'UPLOAD_CONFIG' => array(
        'mimes' => '', // 允许上传的文件MiMe类型
        'maxSize' => 2 * 1024 * 1024, // 上传的文件大小限制 (0-不做限制，默认为2M，后台配置会覆盖此值)
        'autoSub' => true, // 自动子目录保存文件
        'subName' => array('date', 'Y-m-d'), // 子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
        'rootPath' => './Uploads/', // 保存根路径
        'savePath' => '', // 保存路径
        'saveName' => array('uniqid', ''), // 上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
        'saveExt' => '', // 文件保存后缀，空则使用原后缀
        'replace' => false, // 存在同名是否覆盖
        'hash' => true, // 是否生成hash编码
        'callback' => false, // 检测文件是否存在回调函数，如果存在返回文件信息数组
    ),
    //'DATA_CACHE_PREFIX' => 'Redis_',//缓存前缀
    //'DATA_CACHE_TYPE'=>'Redis',//默认动态缓存为Redis
    //'REDIS_RW_SEPARATE' => false, //Redis读写分离 true 开启
    'REDIS_HOST' => '47.94.80.152', //redis服务器ip，多台用逗号隔开；读写分离开启时，第一台负责写，其它[随机]负责读；
    'REDIS_PORT' => '16379', //端口号
    'REDIS_TIMEOUT' => '300', //超时时间
    'REDIS_PERSISTENT' => false, //是否长连接 false=短连接
    'REDIS_AUTH' => 'junglecat@~127.0', //AUTH认证密码
    'DATA_CACHE_TIME' => 10800, // 数据缓存有效期 0表示永久缓存
);

// 获取数据库配置信息，手动修改数据库配置请修改./Data/db.php，这里无需改动

$db_config = include './Data/db.php';  // 包含数据库连接配置
// 如果数据表字段名采用大小写混合需配置此项
$db_config['DB_PARAMS'] = array(\PDO::ATTR_CASE => \PDO::CASE_NATURAL);

// 返回合并的配置
return array_merge(
        $_config, // 系统全局默认配置
        $db_config, // 数据库配置数组
        include APP_PATH . '/Common/Builder/config.php'  // 包含Builder配置
);
