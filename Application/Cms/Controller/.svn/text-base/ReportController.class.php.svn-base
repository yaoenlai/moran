<?php
// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------
namespace Cms\Controller;
use Home\Controller\HomeController;
use Common\Util\Think\Page;
/**
 * 举报控制器
 * @author zxq
 */
class ReportController extends HomeController {
    /**
     * 默认方法
     * @author zxq
     */
    public function index($data_id) {
        if (IS_POST) {
            $report_object = D('Cms/Report');
            $data = $report_object->create();
            if ($data) {
                $result = $report_object->add($data);
                if ($result) {
                    $this->success('您的举报提交成功，请您耐心等待！');
                } else {
                    $this->error($report_object->getError());
                }
            } else {
                $this->error($report_object->getError());
            }
        } else {
            cookie('forward', $_SERVER['REQUEST_URI']);
            $this->assign('info', D('Cms/Index')->detail($data_id));
            $this->assign('reason_list', D('Cms/Report')->reason_list());
            $this->assign('meta_title', '举报页面');
            $this->display($template);
        }
    }
}