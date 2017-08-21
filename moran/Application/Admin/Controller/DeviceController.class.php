<?php
// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: sp
// +----------------------------------------------------------------------
namespace Admin\Controller;
use Common\Util\Think\Page;
/**
 * 设备管理控制器
 * @author sp
 */
class DeviceController extends AdminController {
    /**
     * 设备管理列表
     * @author sp
     */
    public function index() {
        //搜索
        $keyword = I('keyword', '', 'string');
        if( !empty($keyword) ){
            $condition = array('eq',$keyword);
            $map['id|module|mobile'] = array(
                $condition,
                $condition,
                $condition,
                '_multi'=>true
            );
        }

        $p = I('p','','int');

        $object = D("Admin/Device");
        $data_list = $object ->field('*')
            ->where($map)
            ->page($p, C('ADMIN_PAGE_ROWS'))
            ->order('id desc')
            ->select();
        $page = new Page(
            $object->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );

        // 使用Builder快速建立列表页面。
        $builder = new \Common\Builder\ListBuilder();
        $builder->setMetaTitle('设备列表') // 设置页面标题
                ->addTopButton('self',array('title'=>'设备列表'))  // 添加启用按钮
                ->setSearch('请输入设备ID/手机号码/来源模块', U('index'))
                ->addTableColumn('id', '设备ID')
                ->addTableColumn('module', '设备来源')
                ->addTableColumn('sid', '网站ID')
                ->addTableColumn('imeil', 'Imeil')
                ->addTableColumn('uuid', 'UUID')
                ->addTableColumn('mobile', '手机')
                ->addTableColumn('deviceinfo', '设备信息')
                ->addTableColumn('status', '状态', 'status')
                ->addTableColumn('create_time', '创建时间', 'time')
                ->addTableColumn('update_time', '更新时间', 'time')
                ->setTableDataList($data_list)  // 数据列表
                ->setTableDataPage($page->show())  // 数据列表分页
                ->display();
    }
}
