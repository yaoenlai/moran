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
 * 用户字段模型
 * 该类参考了OneThink的部分实现
 * @author huajie <banhuajie@163.com>
 */
class CondModel extends Model{
    /**
     * 数据库表名
     * @author zxq
     */
    protected $tableName = 'love_cond';


    /**
     * 自动验证规则
     * @author sp
     */
    protected $_validate = array(
        array('uid', 'require', '用户ID不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('uid', '', '字段名称被占用', self::MUST_VALIDATE, 'unique', self::MODEL_INSERT),
        array('gender', 'require', '性别不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('startage', 'require', '最小年龄不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('endage', 'require', '最大年龄不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('startheight', 'require', '最小体重不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('endheight', 'require', '最大体重不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('marry', 'require', '婚姻状态不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('lovesort', 'require', '交友类型不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('startedu', 'require', '最低学历不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('endedu', 'require', '最高学历不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        //array('setarea', 'require', '所在区域不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('areas', 'require', '优选区域不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('house', 'require', '住房要求不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('car', 'require', '购车状况不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('avatar', 'require', '图像要求不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('star', 'require', '账户星级要求不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('starup', 'require', '星级判断不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('mustcond', 'require', '必须条件不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    );

    /**
     * 自动完成规则
     * @author sp
     */
    protected $_auto = array(
        array('status', 1, self::MODEL_INSERT, 'string'),
    );
}
