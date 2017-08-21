<?php
// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------
namespace User\Admin;
use Admin\Controller\AdminController;
use Common\Util\Think\Page;
/**
 * 纪录控制器
 * @author zxq
 */
class LogAdmin extends AdminController {
    /**
     * 积分纪录
     * @author zxq
     */
    public function score() {
        //搜索
        $keyword = I('keyword', '', 'string');
        $condition = array('like','%'.$keyword.'%');
        $map['id|UID'] = array($condition, $condition,'_multi'=>true);

        //获取所有消息
        $p = $_GET["p"] ? : 1;
        $ct_object = D('ScoreLog');
        $map['status'] = array('egt', '0'); //禁用和正常状态
        $data_list = $ct_object
                   ->page($p, C('ADMIN_PAGE_ROWS'))
                   ->order('id desc')
                   ->where($map)
                   ->select();
        $page = new Page(
            $ct_object->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );

        //使用Builder快速建立列表页面。
        $builder = new \Common\Builder\ListBuilder();
        $builder->setMetaTitle('积分纪录') //设置页面标题
                ->setSearch('请输入ID/消息标题', U('index'))
                ->addTableColumn('id', 'ID')
                ->addTableColumn('uid', 'UID')
                ->addTableColumn('type', '变动', 'callback', array(D('ScoreLog'), 'change_type'))
                ->addTableColumn('score', '数量')
                ->addTableColumn('message', '消息')
                ->addTableColumn('create_time', '创建时间', 'time')
                ->addTableColumn('status', '状态', 'status')
                ->setTableDataList($data_list) //数据列表
                ->setTableDataPage($page->show()) //数据列表分页
                ->display();
    }

    /**
     * 登录日志
     * @author zxq
     */
    public function login() {
        //搜索
        $keyword = I('keyword', '', 'string');
        $condition = array('like','%'.$keyword.'%');
        $map['id|UID'] = array($condition, $condition,'_multi'=>true);

        //获取所有消息
        $p = $_GET["p"] ? : 1;
        $ct_object = D('LoginLog');
        $map['status'] = array('egt', '0'); //禁用和正常状态
        $data_list = $ct_object
                   ->page($p, C('ADMIN_PAGE_ROWS'))
                   ->order('id desc')
                   ->where($map)
                   ->select();
        $page = new Page(
            $ct_object->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );

        //使用Builder快速建立列表页面。
        $builder = new \Common\Builder\ListBuilder();
        $builder->setMetaTitle('积分纪录') //设置页面标题
                ->setSearch('请输入ID/消息标题', U('index'))
                ->addTableColumn('id', 'ID')
                ->addTableColumn('uid', 'UID')
                ->addTableColumn('ip', 'IP地址', 'callback', 'long2ip')
                ->addTableColumn('type', '登录方式')
                ->addTableColumn('device', '设备信息')
                ->addTableColumn('create_time', '登录时间', 'time')
                ->addTableColumn('status', '状态', 'status')
                ->setTableDataList($data_list) //数据列表
                ->setTableDataPage($page->show()) //数据列表分页
                ->display();
    }
}
