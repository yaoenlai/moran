<?php
// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------
namespace Home\Controller;
use Common\Common\MessageModule;
use Common\Util\Easemob;
use Think\Controller;
use Common\Util\Think\Page;
/**
 * 前台默认控制器
 * @author zxq
 */
class IndexController extends HomeController {

    private   $client_id		=	'YXA6f83TAP1dEeaBdXszhlCfmQ';
    private   $client_secret	=	'YXA63-G5bdQ_qTUHD7rNUEqQ4PoOFds';
    private   $org_name			=	'1113170228178682';
    private   $app_name			=	'myapp001';
    private   $huanxin;
    /**
     * 默认方法
     * @author zxq
     */
    public function index() {
        $options['client_id'] 		= $this->client_id;
        $options['client_secret']	= $this->client_secret;
        $options['org_name']		= $this->org_name;
        $options['app_name']		= $this->app_name;
        $this->huanxin = new Easemob($options);
        $message_module = new MessageModule($this->huanxin);
        $user_data = [
            3 => [
                'uid' => 3,
                'username' => 'xinbin',
                'gender' => -1,
                'hx_uid' => 'ec210850-ff16-11e6-a622-91601468cd83',
            ],
            4 => [
                'uid' => 4,
                'username' => 'yyy',
                'gender' => -1,
                'hx_uid' => 'ec4a1420-ff16-11e6-8099-3752b6b4514a',
            ],

        ];
        $user = array_rand($user_data);
        $message_module->joinMessageQueue($user);
        cookie('forward', C('HOME_PAGE'));
        if (C('INDEX_TEMPLATE')) {
            $template = C('INDEX_TEMPLATE');
        }
        $this->assign('meta_title', "首页");
        $this->display($template ? : '');
    }

    /**
     * 单页类型
     * @author zxq
     */
    public function page($id) {
        $nav_object = D('Admin/Nav');
        $con['id']     = $id;
        $con['status'] = 1;
        $info = $nav_object->where($con)->find();

        cookie('forward', $_SERVER['REQUEST_URI']);
        $this->assign('info', $info);
        $this->assign('meta_title', $info['title']);
        $this->display();
    }

    /**
     * 文章列表
     * @author zxq
     */
    public function lists($cid) {
        $nav_object = D('Admin/Nav');
        $con['id']     = $id;
        $con['status'] = 1;
        $info = $nav_object->where($con)->find();

        // 文章列表
        $map['status'] = 1;
        $map['cid']    = $cid;
        $p = $_GET["p"] ? : 1;
        $post_object = D('Admin/Post');
        $data_list = $post_object
                   ->where($map)
                   ->page($p, C("ADMIN_PAGE_ROWS"))
                   ->order("sort desc,id desc")
                   ->select();
        $page = new Page(
            $post_object->where($map)->count(),
            C("ADMIN_PAGE_ROWS")
        );

        cookie('forward', $_SERVER['REQUEST_URI']);
        $this->assign('data_list', $data_list);
        $this->assign('page', $page->show());
        $this->assign('meta_title', $info['title']);
        $this->display();
    }

    /**
     * 文章详情
     * @author zxq
     */
    public function post($id) {
        $post_object = D('Admin/Post');
        $con['id']     = $id;
        $con['status'] = 1;
        $info = $post_object->where($con)->find();

        // 阅读量加1
        $result = $post_object->where(array('id' => $id))->SetInc('view_count');

        cookie('forward', $_SERVER['REQUEST_URI']);
        $this->assign('info', $info);
        $this->assign('meta_title', $info['title']);
        $this->display('page');
    }

    /**
     * 系统配置
     * @author zxq
     */
    public function config($name = '') {
        $data_list = C($name);
        $this->assign('data_list', $data_list);
        $this->assign('meta_title', '系统配置');
        $this->display();
    }

    /**
     * 导航
     * @author zxq
     */
    public function nav($group = 'wap_bottom') {
        $data_list = D('Admin/Nav')->getNavTree(0, $group);
        $this->assign('data_list', $data_list);
        $this->assign('meta_title', '导航列表');
        $this->display();
    }

    /**
     * 模块
     * @author zxq
     */
    public function module() {
        $map['status'] = 1;
        $data_list = D('Admin/MODULE')->where($map)->select();
        $this->assign('data_list', $data_list);
        $this->assign('meta_title', '模块列表');
        $this->display();
    }
}
