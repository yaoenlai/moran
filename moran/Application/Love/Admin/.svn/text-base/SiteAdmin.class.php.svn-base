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
class SiteAdmin extends CommonAdmin {
    /**
     * 用户列表
     * @author zxq
     */
    public function index() {
        // 搜索
        $keyword   = I('keyword', '', 'string');
        if( !empty($keyword) ){
            $condition = array('like','%'.$keyword.'%');
            $map['id|title'] = array(
                $condition,
                $condition,
                '_multi'=>true
            );
        }

        // 获取所有用户
        $map['status'] = array('egt', '0'); // 禁用和正常状态
        $p = !empty($_GET["p"]) ? $_GET['p'] : 1;
        $object = D('Love/Site');
        $data_list = $object
                   ->page($p , C('ADMIN_PAGE_ROWS'))
                   ->where($map)
                   ->order('id desc')
                   ->select();
		$data_list = $object->toMergaList($data_list);
        $page = new Page(
            $object->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );

        // 使用Builder快速建立列表页面。
        $builder = new \Common\Builder\ListBuilder();
        $builder->setMetaTitle('终端列表') // 设置页面标题
                ->addTopButton('addnew')  // 添加新增按钮
                ->addTopButton('resume')  // 添加启用按钮
                ->addTopButton('forbid')  // 添加禁用按钮
                //->addTopButton('delete')  // 添加删除按钮
                ->setSearch('请输入ID/名称／描述', U('index'))
                ->addTableColumn('id', 'ID')
                //->addTableColumn('ptname', '头像', 'picture')
                ->addTableColumn('title', '站点名')
                ->addTableColumn('uid', '所有者')
                ->addTableColumn('icon', '图标','avatar')
                ->addTableColumn('ver', '版本')
                ->addTableColumn('splash', '启动图','equal_scale')
                ->addTableColumn('devInfo', '开发者')
                ->addTableColumn('isfree', '是否免费','diy_status',$this->boolean)
                ->addTableColumn('status', '状态', 'status')
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($data_list)    // 数据列表
                ->setTableDataPage($page->show()) // 数据列表分页
                ->addRightButton('edit')          // 添加编辑按钮
                ->addRightButton('forbid')        // 添加禁用/启用按钮
                //->addRightButton('recycle')        // 添加删除按钮
                ->display();
    }

    /**
     * 新增用户
     * @author zxq
     */
    public function add() {
        if (IS_POST) {
            $object = D('Love/Site');
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
			$devInfo['uid'] = is_login();
			$devInfo['key'] = date('ymdHi').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), -6);
			$devInfo['secret'] = md5(time().$devInfo['key']);
            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('新增终端') //设置页面标题
                    ->setPostUrl(U('add'))    //设置表单提交地址
					->addFormItem('uid', 'hidden', 'UID', 'UID')
					->addFormItem('key', 'hidden', 'key', 'key')
					->addFormItem('secret', 'hidden', 'secret', 'secret')
                    ->addFormItem('title', 'text', '站点名称', '站点名称')
                    ->addFormItem('ver', 'text', '版本', '版本号如1.0.0')
                    ->addFormItem('protocol', 'textarea', '服务协议', '服务协议')
                    ->addFormItem('about', 'textarea', '关于我们', '关于我们')
                    ->addFormItem('copyright', 'textarea', '版权信息', '版权信息')
                    ->addFormItem('isfree', 'radio', '是否免费', '是否免费版,如果免费版将隐藏全部的支付相关的功能变为免费使用',$this->boolean)
                    ->addFormItem('icon', 'picture', '图标', '站点图标,必须方形图')
                    ->addFormItem('splash', 'picture', '启动图', '启动图,要求分辨率为1080*1920(竖版)')
                    ->addFormItem('detail', 'textarea', '站点描述', '站点描述')
                    ->addFormItem('backurl', 'text', '通知地址', '通知地址')
                    ->addFormItem('sort', 'text', '排序', '排序')
                    ->setFormData($devInfo)
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
            $object = D('Love/Site');
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
            $info = D('Love/Site')->find($id);

            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('编辑预设信息')  // 设置页面标题
                    ->setPostUrl(U('edit'))    // 设置表单提交地址
                    ->addFormItem('id', 'hidden', 'ID', 'ID')
					->addFormItem('id', 'static', '站点ID', 'SID')
                    ->addFormItem('uid', 'hidden', 'UID', 'UID')
					->addFormItem('key', 'hidden', 'key', 'key')
					->addFormItem('key', 'static', 'Key', 'Key')
					->addFormItem('secret', 'hidden', 'secret', 'secret')
					->addFormItem('secret', 'static', 'Secret', 'Secret')
                    ->addFormItem('title', 'text', '站点名称', '站点名称')
                    ->addFormItem('ver', 'text', '版本', '版本号如1.0.0')
                    ->addFormItem('protocol', 'textarea', '服务协议', '服务协议')
                    ->addFormItem('about', 'textarea', '关于我们', '关于我们')
                    ->addFormItem('copyright', 'textarea', '版权信息', '版权信息')
                    ->addFormItem('isfree', 'radio', '是否免费', '是否免费版,如果免费版将隐藏全部的支付相关的功能变为免费使用',$this->boolean)
                    ->addFormItem('icon', 'picture', '图标', '站点图标,必须方形图')
                    ->addFormItem('splash', 'picture', '启动图', '启动图,要求分辨率为1080*1920(竖版)')
                    ->addFormItem('detail', 'textarea', '站点描述', '站点描述')
                    ->addFormItem('backurl', 'text', '通知地址', '通知地址')
                    ->addFormItem('sort', 'text', '排序', '排序')
                    ->setFormData($info)
                    ->display();
        }
    }
}
