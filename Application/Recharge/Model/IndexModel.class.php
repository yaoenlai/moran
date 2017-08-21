<?php
// +----------------------------------------------------------------------
// | OpenCMF [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.opencmf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace Recharge\Model;
use Think\Model;
/**
 * 应用模型
 * @author jry <598821125@qq.com>
 */
class IndexModel extends Model {
    /**
     * 数据库表名
     * @author jry <598821125@qq.com>
     */
    protected $tableName = 'recharge_index';

    /**
     * 自动验证规则
     * @author jry <598821125@qq.com>
     */
    protected $_validate = array(
    	array('uid', 'require', 'userID不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('out_trade_no','require','订单号错误', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('out_trade_no', '', '订单号已存在', self::MUST_VALIDATE, 'unique', self::MODEL_BOTH),
        array('money','require','请填写金额', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('pay_type','require','支付方式错误', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    );

    /**
     * 自动完成规则
     * @author jry <598821125@qq.com>
     */
    protected $_auto = array(
        array('is_pay', '0', self::MODEL_INSERT),
        array('is_callback', '0', self::MODEL_INSERT),
        //array('uid', 'is_login', self::MODEL_INSERT, 'function'),
        array('create_time', 'time', self::MODEL_INSERT, 'function'),
        array('update_time', 'time', self::MODEL_BOTH, 'function'),
        array('status', '0', self::MODEL_INSERT),
    );

    /**
     *根据条件查询充值余额
     * @param array $where 查询条件
     * @param int   $page  页码
     * @param int   $offset 条数
     * @return array
     * */
    public function getRechargeList($where,$page = 1,$offset = 5){
        $count = D('Recharge/Index')->where($where)->order('create_time desc')->count();
        $total = ceil($count/$offset);//总页数  = 总条数/条数
        $page = ($page - 1) * $offset;//从$page开始
        $data = D('Recharge/Index')->where($where)->order('create_time desc')->limit($page,$offset)->select();
        $recharge['data'] = $data;
        $recharge['total'] = $total;
        return $recharge;
    }
}
