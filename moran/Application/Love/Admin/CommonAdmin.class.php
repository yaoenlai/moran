<?php
// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------
namespace Love\Admin;
use Admin\Controller\AdminController;
use Common\Util\Think\Page;
/**
 * 用户控制器
 * @author zxq
 */
class CommonAdmin extends AdminController {
	
	
	protected $boolean = array('1' => '是', '0' => '否');
	
    /**
     * 初始化方法
     * @author zxq
     */
    protected function _initialize() {
		parent::_initialize();
        
    }
}
