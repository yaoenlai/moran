<?php
// +----------------------------------------------------------------------
// | OpenCMF [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.opencmf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace Recharge\Admin;
use Admin\Controller\AdminController;
use Common\Util\Think\Page;
/**
 * 默认控制器
 * @author jry <598821125@qq.com>
 */
class IndexAdmin extends AdminController {
    /**
     * 默认方法
     * @author jry <598821125@qq.com>
     */
    public function index() {
    	 // 搜索
        $keyword   = I('keyword', '', 'string');
		if(!empty($keyword)){
			$condition = array('like','%'.$keyword.'%');
	        $map['out_trade_no|title'] = array( 
	            $condition,
	            $condition,
	            '_multi'=>true
	        );
		}
        
        // 获取所有记录
        $map['status'] = array('egt', '0'); // 禁用和正常状态
        $p = !empty($_GET["p"]) ? $_GET['p'] : 1;
        $object = D('Recharge/Index');
        $data_list = $object
                   ->page($p , C('ADMIN_PAGE_ROWS'))
                   ->where($map)
                   ->order('id desc')
                   ->select();
        $page = new Page(
            $object->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );

        // 使用Builder快速建立列表页面。
        $builder = new \Common\Builder\ListBuilder();
        $builder->setMetaTitle('充值纪录') // 设置页面标题
        		->addTopButton('resume')  // 添加启用按钮
                ->setSearch('请输入订单号/商品名称', U('index'))
                ->addTableColumn('id', 'ID')
                ->addTableColumn('uid', '用户ID')
                ->addTableColumn('title', '标题')
                ->addTableColumn('out_trade_no', '订单号')
                ->addTableColumn('money', '充值金额','callback', 'moneyFormat')
                ->addTableColumn('pay_type', '付款方式')
                ->addTableColumn('is_pay', '支付结果', 'status')
                ->addTableColumn('is_callback', '是否回调', 'status')
                ->addTableColumn('create_time', '注册时间', 'time')
                ->addTableColumn('status', '状态', 'status')
                ->addTableColumn('callfunc', '回调操作')
                ->setTableDataList($data_list)    // 数据列表
                ->setTableDataPage($page->show()) // 数据列表分页
                ->display();
    }
}