<?php
// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------
namespace Admin\Model;
use Think\Model;
/**
 * 插件模型
 * 该类参考了OneThink的部分实现
 * @author zxq
 */
class SysNicksModel extends Model {
    /**
     * 数据库表名
     * @author zxq
     */
    protected $tableName = 'admin_sysnicks';

    public function randomNickname($gender = 1){
        $userWhere = "t1.id >= t2.id";
        $userMax = D('Admin/SysNicks')->field(" MAX(id)")->buildSql();
        $userMin = D('Admin/SysNicks')->field(" MIN(id)")->buildSql();
        $userSql = "(SELECT ROUND(RAND() * ($userMax - $userMin) + $userMin) as id)";
        $field = ($gender == '-1') ? "fname" : "mname";
        $userModel = D('Admin/SysNicks')->alias('t1')->field($field)->join($userSql. ' t2')->where($userWhere)->find();
        return $userModel[$field];
    }

}
