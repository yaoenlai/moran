<?php
// +----------------------------------------------------------------------
// | OpenCMF [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.opencmf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: yyl <94677073@qq.com>
// +----------------------------------------------------------------------
namespace Recharge\Model;
use Think\Model;
/**
 * 应用模型
 * @author yyl <94677073@qq.com>
 */
class LogModel extends Model {
    /**
     * 数据库表名
     * @author yyl <94677073@qq.com>
     */
    protected $tableName = 'recharge_log';

    /**
     * 自动验证规则
     * @author yyl <94677073@qq.com>
     */
    protected $_validate = array(
        //保证用户不能为空
        array('uid','require','操作用户不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        // array('username','require','用户名不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        // //保证用户存在
    );

    /**
     * 自动完成规则
     * @author yyl <94677073@qq.com>
     */
    protected $_auto = array(
        array('create_time', 'time', self::MODEL_INSERT,'function'),
    );
}
