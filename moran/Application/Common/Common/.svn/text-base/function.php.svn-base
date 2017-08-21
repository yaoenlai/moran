<?php
// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------

require_once(APP_PATH . '/Common/Common/addon.php'); //加载插件相关公共函数库
require_once(APP_PATH . '/Common/Common/developer.php'); //加载开发者二次开发公共函数库

/**
 * POST数据提前处理
 * @return array
 * @author zxq
 */
function format_data($data = null) {
    //解析数据类似复选框类型的数组型值
    if (!$data) {
        $data = $_POST;
    }
    $data_object = new \Common\Util\Think\Date;
    foreach($data as $key => $val){
        if (is_array($val)) {
            $data[$key] = implode(',', $val);
        } else if ($data_object->checkDatetime($val)) {
            $data[$key] = strtotime($val);
        } else if ($data_object->checkDatetime($val, 'Y-m-d H:i')) {
            $data[$key] = strtotime($val);
        } else if ($data_object->checkDatetime($val, 'Y-m-d')) {
            $data[$key] = strtotime($val);
        }
    }
    return $data;
}

/**
 * 获取所有数据并转换成一维数组
 * @author zxq
 */
function select_list_as_tree($model, $map = null, $extra = null, $key = 'id') {
    //获取列表
    $con['status'] = array('eq', 1);
    if ($map) {
        $con = array_merge($con, $map);
    }
    $model_object = D($model);
    if (in_array('sort', $model_object->getDbFields())) {
        $list = $model_object->where($con)->order('sort asc, id asc')->select();
    } else {
        $list = $model_object->where($con)->order('id asc')->select();
    }

    //转换成树状列表(非严格模式)
    $tree = new \Common\Util\Tree();
    $list = $tree->toFormatTree($list, 'title', 'id', 'pid', 0, false);

    if ($extra) {
        $result[0] = $extra;
    }

    //转换成一维数组
    foreach ($list as $val) {
        $result[$val[$key]] = $val['title_show'];
    }
    return $result;
}

/**
 * 解析文档内容
 * @param string $str 待解析内容
 * @return string
 * @author zxq
 */
function parse_content($str) {
    // 将img标签的src改为lazy-src用户前台图片lazyload加载
    if (C('STATIC_DOMAIN')) {
        $tmp = preg_replace('/<img.*?src="(.*?Uploads.*?)"(.*?)>/i', "<img class='lazy lazy-fadein img-responsive' style='display:inline-block;' data-src='".C('STATIC_DOMAIN')."$1'>", $str);
        $tmp = preg_replace('/<img.*?src="(\/.*?)"(.*?)>/i', "<img class='img-responsive' style='display:inline-block;' src='".C('STATIC_DOMAIN')."$1'>", $tmp);
    } else {
        $domain = (is_ssl()?'https://':'http://').$_SERVER['HTTP_HOST'];
        $tmp = preg_replace('/<img.*?src="(.*?Uploads.*?)"(.*?)>/i', "<img class='lazy lazy-fadein img-responsive' style='display:inline-block;' data-src='".$domain."$1'>", $str);
        $tmp = preg_replace('/<img.*?src="(\/.*?)"(.*?)>/i', "<img class='img-responsive' style='display:inline-block;' src='".$domain."$1'>", $tmp);
    }
    return $tmp;
}

/**
 * 字符串截取(中文按2个字符数计算)，支持中文和其他编码
 * @static
 * @access public
 * @param str $str 需要转换的字符串
 * @param str $start 开始位置
 * @param str $length 截取长度
 * @param str $charset 编码格式
 * @param str $suffix 截断显示字符
 * @return str
 */
function cut_str($str, $start, $length, $charset='utf-8', $suffix = true) {
    return \Common\Util\Think\Str::cutStr(
        $str, $start, $length, $charset, $suffix
    );
}

/**
 * 过滤标签，输出纯文本
 * @param string $str 文本内容
 * @return string 处理后内容
 * @author zxq
 */
function html2text($str) {
   return \Common\Util\Think\Str::html2text($str);
}

/**
 * 友好的时间显示
 * @param int    $sTime 待显示的时间
 * @param string $type  类型. normal | mohu | full | ymd | other
 * @param string $alt   已失效
 * @return string
 * @author zxq
 */
function friendly_date($sTime, $type = 'mohu', $alt = 'false') {
    $date = new \Common\Util\Think\Date((int)$sTime);
    return $date->friendlyDate($type, $alt);
}

/**
 * 时间戳格式化
 * @param int $time
 * @return string 完整的时间显示
 * @author zxq
 */
function time_format($time = NULL, $format='Y-m-d H:i') {
    $time = $time === NULL ? time() : intval($time);
    return date($format, $time);
}

/**
 * 系统非常规MD5加密方法
 * @param  string $str 要加密的字符串
 * @return string
 * @author zxq
 */
function user_md5($str, $auth_key) {
    if (!$auth_key) {
        $auth_key = C('AUTH_KEY') ? : 'Wiera';
    }
    return '' === $str ? '' : md5(sha1($str) . $auth_key);
}

/**
 * 检测用户是否登录
 * @return integer 0-未登录，大于0-当前登录用户ID
 * @author zxq
 */
function is_login() {
    return D('Admin/User')->is_login();
}

/**
 * 检测用户是否VIP
 * @return integer VIP等级
 * @author zxq
 */
function is_vip($uid) {
    if (D('Admin/Module')->where('name="Vip" and status="1"')->count()) {
        $uid = $uid ? $uid : is_login();
        return D('Vip/Index')->isVip($uid);
    }
    return false;
}

/**
 * 根据用户ID获取用户信息
 * @param  integer $id 用户ID
 * @param  string $field
 * @return array  用户信息
 * @author jry <598821125@qq.com>
 */
function get_user_info($id, $field) {
    $userinfo = D('Admin/User')->find($id);
    $userinfo['avatar_url'] = get_cover($userinfo['avatar'], 'avatar');
    if ($userinfo[$field]) {
        return $userinfo[$field];
    }
    return $userinfo;
}

/**
 * 获取上传文件路径
 * @param  int $id 文件ID
 * @return string
 * @author zxq
 */
function get_cover($id = null, $type = null) {
    return D('Admin/Upload')->getCover($id, $type);
}
