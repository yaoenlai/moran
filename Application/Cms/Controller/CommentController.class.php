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
 * 评论控制器
 * @author zxq
 */
class CommentController extends HomeController {
    /**
     * 评论列表
     * @author zxq
     */
    public function index($data_id, $limit = 10, $page = 1, $order = '', $con = null) {
        $comment_object = D('Comment');
        $list = $comment_object->getCommentList($data_id, $limit, $page, $order, $con);
        $this->success('评论列表', '', array('data' => $list));
    }

    /**
     * 新增评论
     * @author zxq
     */
    public function add() {
        if (IS_POST) {
            $uid = $this->is_login();
            $comment_object = D(D('Index')->moduleName.'/Comment');
            $data = $comment_object->create();
            if ($data) {
                $result = $comment_object->addNew($data);
                if ($result) {
                    $this->success('评论成功');
                } else {
                    $this->error('评论失败'.$comment_object->getError());
                }
            } else {
                $this->error($comment_object->getError());
            }
        }
    }
}