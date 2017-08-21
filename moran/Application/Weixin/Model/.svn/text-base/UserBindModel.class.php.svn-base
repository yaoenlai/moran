<?php
// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------
namespace Weixin\Model;
use Think\Model;
/**
 * 微信用户与CT用户绑定模型
 * @author zxq
 */
class UserBindModel extends Model {
    /**
     * 数据库真实表名
     * 一般为了数据库的整洁，同时又不影响Model和Controller的名称
     * 我们约定每个模块的数据表都加上相同的前缀，比如微信模块用weixin作为数据表前缀
     * @author zxq
     */
    protected $tableName = 'weixin_user_bind';

    /**
     * 自动验证规则
     * @author zxq
     */
    protected $_validate = array(
        array('uid', 'require', 'uid不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('openid', 'require', 'openid不能为空', self::VALUE_VALIDATE, 'regex', self::MODEL_BOTH),
    );

    /**
     * 自动完成规则
     * @author zxq
     */
    protected $_auto = array(
        array('create_time', 'time', self::MODEL_INSERT, 'function'),
        array('update_time', 'time', self::MODEL_BOTH, 'function'),
        array('status', '1', self::MODEL_INSERT),
    );
}