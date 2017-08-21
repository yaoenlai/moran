<?php
// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------
namespace Love\Model;
use Think\Model;
/**
 * 用户隐私信息模型
 * 该类参考了OneThink的部分实现
 * @author huajie <banhuajie@163.com>
 */
class AttrModel extends Model{
    /**
     * 数据库表名
     * @author zxq
     */
    protected $tableName = 'love_attr';

    /**
     * 自动验证规则
     * @author sp
     */
    protected $_validate = array(
        array('uid', 'require', '用户ID不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('uid', '', '字段名称被占用', self::MUST_VALIDATE, 'unique', self::MODEL_INSERT),
        array('privacy', 'require', '私密方式不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
      //  array('realname', 'require', '真实姓名不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    );

    /**
     * 自动完成规则
     * @author sp
     */
    protected $_auto = array(
        array('status', 1, self::MODEL_INSERT, 'string'),
    );
}
