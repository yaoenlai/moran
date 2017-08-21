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
class ParamterAdmin extends CommonAdmin {
    /**
     * 用户列表
     * @author zxq
     */
    public function index() {
        // 搜索
        $keyword   = I('keyword', '', 'string');
        $condition = array('like','%'.$keyword.'%');
        $map['id|ptname|ptdec'] = array(
            $condition,
            $condition,
            $condition,
            '_multi'=>true
        );

        // 获取所有用户
        $map['status'] = array('egt', '0'); // 禁用和正常状态
        $p = !empty($_GET["p"]) ? $_GET['p'] : 1;
        $object = D('Love/Paramter');
        $data_list = $object
                   ->page($p , C('ADMIN_PAGE_ROWS'))
                   ->where($map)
                   ->order('id asc')
                   ->select();
        $page = new Page(
            $object->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );

        // 使用Builder快速建立列表页面。
        $builder = new \Common\Builder\ListBuilder();
        $builder->setMetaTitle('用户列表') // 设置页面标题
                ->addTopButton('addnew')  // 添加新增按钮
                ->addTopButton('resume')  // 添加启用按钮
                ->addTopButton('forbid')  // 添加禁用按钮
                ->setSearch('请输入ID/名称／描述', U('index'))
                ->addTableColumn('id', 'ID')
                //->addTableColumn('ptname', '头像', 'picture')
                ->addTableColumn('ptname', '名称')
                ->addTableColumn('ptdec', '描述')
                ->addTableColumn('pttype', '类型')
                ->addTableColumn('status', '状态', 'status')
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($data_list)    // 数据列表
                ->setTableDataPage($page->show()) // 数据列表分页
                ->addRightButton('edit')          // 添加编辑按钮
                ->addRightButton('forbid')        // 添加禁用/启用按钮
                ->display();
    }

    /**
     * 新增用户
     * @author zxq
     */
    public function add() {
        if (IS_POST) {
            $object = D('Love/Paramter');
            $data = $object->create();
            if ($data) {
                $id = $object->add();
                if ($id) {
                    $this->success('新增成功', U('index'));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($object->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('新增用户') //设置页面标题
                    ->setPostUrl(U('add'))    //设置表单提交地址
                    //->addFormItem('reg_type', 'hidden', '注册方式', '注册方式')
                    ->addFormItem('ptname', 'text', '名称', '字段名称', select_list_as_tree('User/Type'))
                    ->addFormItem('ptvalue', 'textarea', '字段选项', '字段选项')
                    ->addFormItem('ptdec', 'text', '字段描述', '字段描述')
                    ->addFormItem('sort', 'text', '排序', '排序')
                    ->addFormItem('pttype', 'radio', '字段类型', '字段类型', array('select' => 'select', 'radio' => 'radio','checkbox' => 'checkbox'))
                    ->addFormItem('issystem', 'radio', '是否系统分类', '是否系统分类', array('1' => '是', '0' => '否'))
                    //->setFormData(array('reg_type' => 'admin'))
                    ->display();
        }
    }

    /**
     * 编辑用户
     * @author zxq
     */
    public function edit($id) {
        if (IS_POST) {
            // 提交数据
            $object = D('Love/Paramter');
            $data = $object->create();
            if ($data) {
                $result = $object->save($data);
                if ($result) {
                    $this->success('更新成功', U('index'));
                } else {
                    $this->error('更新失败', $object->getError());
                }
            } else {
                $this->error($object->getError());
            }
        } else {
            // 获取账号信息
            $info = D('Love/Paramter')->find($id);

            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('编辑预设信息')  // 设置页面标题
                    ->setPostUrl(U('edit'))    // 设置表单提交地址
                    ->addFormItem('id', 'hidden', 'ID', 'ID')
                    ->addFormItem('ptname', 'text', '名称', '字段名称', select_list_as_tree('User/Type'))
                    ->addFormItem('ptvalue', 'textarea', '字段选项', '字段选项')
                    ->addFormItem('ptdec', 'text', '字段描述', '字段描述')
                    ->addFormItem('sort', 'text', '排序', '排序')
                    ->addFormItem('pttype', 'radio', '字段类型', '字段类型', array('select' => 'select', 'radio' => 'radio','checkbox' => 'checkbox'))
                    ->addFormItem('issystem', 'radio', '是否系统分类', '是否系统分类', array('1' => '是', '0' => '否'))
                    ->setFormData($info)
                    ->display();
        }
    }

    /**
     * 设置一条或者多条数据的状态
     * @author zxq
     */
    public function setStatus($model = CONTROLLER_NAME){
        $ids = I('request.ids');
        if (is_array($ids)) {
            if(in_array('1', $ids)) {
                $this->error('超级管理员不允许操作');
            }
        } else {
            if($ids === '1') {
                $this->error('超级管理员不允许操作');
            }
        }
        parent::setStatus($model);
    }
}
