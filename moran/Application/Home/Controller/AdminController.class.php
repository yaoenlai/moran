<?php
// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------
namespace Home\Controller;
use Think\Controller;
/**
 * 跳转到后台控制器
 * @author zxq
 */
class AdminController extends Controller {
    /**
     * 自动跳转到后台入口文件
     * @author zxq
     */
    public function index() {
        redirect(C('HOME_PAGE').'/admin.php');
    }
}
