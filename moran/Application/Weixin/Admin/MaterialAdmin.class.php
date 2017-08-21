<?php
// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------
namespace Weixin\Admin;
use Admin\Controller\AdminController;
use Common\Util\Think\Page;
/**
 * 素材控制器
 * @author zxq
 */
class MaterialAdmin extends AdminController {
    /**
     * 素材列表
     * @author zxq
     */
    public function index() {
        //获取所有素材
        $map['status'] = array('egt', '0'); //禁用和正常状态
        $p = !empty($_GET["p"]) ? $_GET['p'] : 1;
        $data_list = D('Material')
                   ->page($p, C('ADMIN_PAGE_ROWS'))
                   ->where($map)
                   ->order('id desc')
                   ->select();
        $page = new Page(
            D('Material')->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );

        // 使用Builder快速建立列表页面。
        $builder = new \Common\Builder\ListBuilder();
        $builder->setMetaTitle('素材列表')  // 设置页面标题
                ->addTopButton('addnew')   // 添加新增按钮
                ->addTopButton('delete')   // 添加删除按钮
                ->addTableColumn('id', 'ID')
                ->addTableColumn('cover', '封面', 'picture')
                ->addTableColumn('title', '标题')
                ->addTableColumn('ctime', '创建时间', 'time')
                ->addTableColumn('status', '状态', 'status')
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($data_list)     // 数据列表
                ->setTableDataPage($page->show())  // 数据列表分页
                ->addRightButton('edit')           // 添加编辑按钮
                ->addRightButton('forbid')  // 添加禁用/启用按钮
                ->addRightButton('delete')  // 添加删除按钮
                ->display();
    }

    /**
     * 新增素材
     * @author zxq
     */
    public function add() {
        if(IS_POST){
            $material_object = D('Material');
            $data = $material_object->create();
            if($data){
                $id = $material_object->add();
                if($id){
                    $this->success('新增成功', U('index'));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($material_object->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('新增素材')  // 设置页面标题
                    ->setPostUrl(U('add'))     // 设置表单提交地址
                    ->addFormItem('title', 'text', '素材名称', '素材名称')
                    ->addFormItem('author', 'text', '作者', '作者')
                    ->addFormItem('cover', 'picture', '封面', '封面图片')
                    ->addFormItem('abstract', 'textarea', '简介', '简介')
                    ->addFormItem('content', 'kindeditor', '正文内容', '正文内容')
                    ->addFormItem('url', 'text', '外链', '外链／原文地址')
                    ->addFormItem('sort', 'num', '排序', '用于显示的顺序')
                    ->display();
        }
    }

    /**
     * 编辑素材
     * @author zxq
     */
    public function edit($id){
        if(IS_POST){
            $material_object = D('Material');
            $data = $material_object->create();
            if($data){
                if($material_object->save()!== false){
                    $this->success('更新成功', U('index'));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($material_object->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('编辑素材')  // 设置页面标题
                    ->setPostUrl(U('edit'))    // 设置表单提交地址
                    ->addFormItem('id', 'hidden', 'ID', 'ID')
                    ->addFormItem('title', 'text', '素材名称', '素材名称')
                    ->addFormItem('author', 'text', '作者', '作者')
                    ->addFormItem('cover', 'picture', '封面', '封面图片')
                    ->addFormItem('abstract', 'textarea', '简介', '简介')
                    ->addFormItem('content', 'kindeditor', '正文内容', '正文内容')
                    ->addFormItem('url', 'text', '外链', '外链／原文地址')
                    ->addFormItem('sort', 'num', '排序', '用于显示的顺序')
                    ->setFormData(D('Material')->find($id))
                    ->display();
        }
    }
}