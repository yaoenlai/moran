<?php
// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------
namespace Weixin\Controller;
use Home\Controller\HomeController;
/**
 * 默认控制器
 * @author zxq
 */
class MaterialController extends HomeController {
    /**
     * 阅读素材
     * @author zxq
     */
    public function detail($id) {
        // 获取信息
        $material_object = D('Material');
        $info = $material_object->find($id);

        // 阅读量加1
        $result = $material_object->where(array('id' => $id))->SetInc('view_count');

        $this->assign('info', $info);
        $this->assign('meta_title', $info['title']);
        cookie('forward', $_SERVER['REQUEST_URI']);
        $this->display();
    }
}
