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
class ProfileModel extends Model{
    /**
     * 数据库表名
     * @author sp
     */
    protected $tableName = 'love_profile';

    /**
     * 自动验证规则
     * @author sp
     */
    protected $_validate = array(
        array('uid', 'require', '用户ID不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('uid', '', '用户ID被占用', self::MUST_VALIDATE, 'unique', self::MODEL_INSERT),
        //array('provinceid', 'require', '省份ID不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        //array('cityid', 'require', '城市ID不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        //array('birthday', 'require', '生日不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        //array('marrystatus', 'require', '婚姻状态不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    );

}
