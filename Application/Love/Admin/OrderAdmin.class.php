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
 * 用户资料控制器
 * @author sp
 */
class OrderAdmin extends CommonAdmin {
    public function index(){
        //搜索
        $keyword = I('keyword', '', 'string');
        if( !empty($keyword) ){
            $condition = array('eq',$keyword);
            $map['uid|orderid'] = array(
                $condition,
                $condition,
                '_multi'=>true
            );
        }

        $object = D("Love/Order");
		$map['status'] = array('egt', '0'); // 禁用和正常状态
		$p = !empty($_GET["p"]) ? $_GET['p'] : 1;
        $data_list = $object
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
        $builder->setMetaTitle('消费记录') // 设置页面标题
            ->addTopButton('self',array('title'=>'补单'))  // 自定义按钮
            ->setSearch('请输入用户ID/订单编号', U('index'))
            ->addTableColumn('id', 'ID')
            ->addTableColumn('uid', '用户ID')
            ->addTableColumn('productname', '商品名称')
            ->addTableColumn('orderid', '订单编号')
            ->addTableColumn('package_id', '套餐编号')
            ->addTableColumn('money', '金额','callback', 'moneyFormat')
            ->addTableColumn('pay_type', '支付商')
            ->addTableColumn('channel_type', '支付方式')
            ->addTableColumn('status', '交易状态', 'status')
            ->addTableColumn('create_time', '消费时间','datetime')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list)  // 数据列表
            ->setTableDataPage($page->show())  // 数据列表分页
            ->display();
    }
}
