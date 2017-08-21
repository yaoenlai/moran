<?php
// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: sp
// +----------------------------------------------------------------------
namespace Admin\Controller;
use \Common\Util\Tree;
use Common\Util\Think\Page;
/**
 * 用户地区管理控制器
 * @author sp
 */
class AreaController extends AdminController {
    /**
     * 地区管理列表
     * @author sp
     */
    public function index() {
        //搜索
        $keyword = I('keyword', '', 'string');
        if( !empty($keyword) ){
            $cond = array('eq',$keyword);
            $condition = array('like','%'.$keyword.'%');
            $map['id|areaname'] = array(
                $cond,
                $condition,
                '_multi'=>true
            );
            $top = false;   //是否查找省级地区
        }else{
            $top = true;
            $map['rootid'] = 0;
            $limit = 1;
        }

        $p = I('p','1','int');

        //由于每个省级以下地区都比较多，所以每页显示 $limit 个省级区域的所有下级信息
        $object = D("Admin/Area");
        $data_list = $object ->field('id,areaname,rootid,depth,status,spreadname')
            ->where($map)
            ->page($p, $limit)
            ->order('id')
            ->select();
        $data_list = $object->areaListToTree($data_list,$top);
        //var_dump($data_list);
        $page = new Page(
            $object->where($map)->count(),$limit
        );

        // 使用Builder快速建立列表页面。
        $builder = new \Common\Builder\ListBuilder();
        $builder->setMetaTitle('地区列表') // 设置页面标题
                ->addTopButton('addnew')  // 添加新增按钮
                ->addTopButton('resume')  // 添加启用按钮
                ->addTopButton('forbid')  // 添加禁用按钮
                //->addTopButton('delete')  // 添加删除按钮
                ->setSearch('请输入地区ID/地区名称', U('index'))
                ->addTableColumn('id', '地区ID')
                ->addTableColumn('areaname', '地区名称')
                ->addTableColumn('spreadname', '级别')
                ->addTableColumn('depth', '排序')
                ->addTableColumn('status', '状态', 'status')
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($data_list)  // 数据列表
                ->setTableDataPage($page->show())  // 数据列表分页
                ->addRightButton('edit', array('href' => U('edit', array('id' => '__data_id__'))))  // 添加编辑按钮
                ->addRightButton('forbid')      // 添加禁用/启用按钮
                //->addRightButton('delete')      // 添加删除按钮
                ->display();
    }

    /**
     * 新增地区管理
     * @author sp
     */
    public function add() {
        if (IS_POST) {
            $nav_object = D('Admin/Area');
            $rootid = I('rootid','','int');
            if( empty($rootid) ){
                $_POST['depth'] = 0;
            }else{
                $_POST['depth'] = 1;
            }
            $data = $nav_object->create();
            if ($data) {
                $id = $nav_object->add($data);
                if ($id) {
                    $this->success('新增成功', U('index', array('group' => $group)));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($nav_object->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('新增导航')  // 设置页面标题
                    ->setPostUrl(U('add'))     // 设置表单提交地址
                    ->addFormItem('rootid', 'select', '上级级别', '上级导航', D("Admin/Area")->areaTreeForSelect())
                    ->addFormItem('areaname', 'text', '地区名称', '级别名称,如西安、郑州等')
                    ->addFormItem('spreadname', 'text', '级别名称', '级别名称,如市、省等')
                    ->addFormItem('orders', 'num', '排序', '排序')
                    ->display();
        }
    }

    /**
     * 编辑地区管理
     * @author sp
     */
    public function edit() {
        if (IS_POST) {
            $object = D('Admin/Area');
            $rootid = I('rootid','','int');
            if( empty($rootid) ){
                $_POST['depth'] = 0;
            }else{
                $_POST['depth'] = 1;
            }
            $data = $object->create();
            if ($data) {
                if ($object->save($data)) {
                    $this->success('更新成功', U('index'));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($object->getError());
            }
        } else {
            $id = I('id','','int');
            $object = D("Admin/Area");
            $info = $object ->field('id,areaname,rootid,depth,status,spreadname')->find($id);

            //var_dump($info);
            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('编辑导航')  // 设置页面标题
                    ->setPostUrl(U(''))    // 设置表单提交地址
                    ->addFormItem('id', 'hidden', 'ID', 'ID')
                    ->addFormItem('rootid', 'select', '上级地区', '上级级别', $object->areaTreeForSelect())
                    ->addFormItem('areaname', 'text', '地区名称', '地区名称,如西安、郑州等')
                    ->addFormItem('spreadname', 'text', '级别名称', '级别名称,如市、省等')
                    ->addFormItem('orders', 'num', '排序', '排序')
                    ->setFormData($info)
                    ->setExtraHtml($this->extra_html)
                    ->display();
        }
    }
}
