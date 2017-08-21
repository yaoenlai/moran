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
 * 消息模型
 * @author zxq
 */
class GreetrobotModel extends Model {
    /**
     * 数据库表名
     * @author zxq
     */
    protected $tableName = 'love_greetrobot';

    /**
     * 自动验证规则
     * @author zxq
     */
    protected $_validate = array(
        array('uid','require','用户ID不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('robot_uid','require','机器人UID', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    );

    /**
     * 自动完成规则
     * @author zxq
     */
    protected $_auto = array(
        array('create_time', 'time', self::MODEL_INSERT, 'function'),
        array('update_time', 'time', self::MODEL_BOTH, 'function'),
        array('times', '0', self::MODEL_INSERT),
        array('status', '1', self::MODEL_INSERT),
    );
}
