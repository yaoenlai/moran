<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.corethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq <http://www.corethink.cn>
// +----------------------------------------------------------------------
namespace Cms\Controller;
use Home\Controller\HomeController;
use Common\Util\Think\Page;
/**
 * 幻灯片控制器
 * @author zxq
 */
class SliderController extends HomeController {
    /**
     * 默认方法
     * @author zxq
     */
    public function index($limit = 5, $page = 1, $order = '') {
        $map['status'] = 1;
        $list = D("Cms/slider")->getList($limit, $page, $order, $map);
        $this->success('幻灯片列表', '', array('data' => $list));
    }
}
