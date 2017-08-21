<?php
// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------
namespace Love\Controller;
use Home\Controller\HomeController;
/**
 * 消息控制器
 * @author zxq
 */
class CommonController extends HomeController{
	
	//不需要限制权限的模块
	private $noAuthCtl = array('Center','Index','User','Order');
	
    /**
     * 初始化方法
     * @author zxq
     */
    protected function _initialize(){
        parent::_initialize();
		if(!in_array(CONTROLLER_NAME, $this->noAuthCtl)){
			$this->is_login();
		}
		//$this->assign('_module_layout', C('MODULE_LAYOUT'));
    }
}
