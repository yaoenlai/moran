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
class HxUserModel extends Model{
    /**
     * 数据库表名
     * @author zxq
     */
    protected $tableName = 'addon_hxuser';

    protected $user_mode = null;
    /**
     * 自动完成规则
     * @author zxq
     */
    protected $_auto = array(
        array('create_time', 'time', self::MODEL_INSERT, 'function'),
        array('update_time', 'time', self::MODEL_BOTH, 'function'),
        array('status', 1, self::MODEL_INSERT, 'string'),
    );

    public function _initialize() {
        $this->user_mode = D('Admin/User');
    }

    /**
     * 根据用户uid 获取环信用户信息
     * @author kevin
     */
    public function huanxinInfo($uid) {
        $data = $this->where(['uid' => $uid])->find();
        if($data) {
            return $data;
        } else {
            return false;
        }
    }

    /**
     * 获取单个用户通过 环信uuid
     * @param $hxuuid
     * @param $hxuuid
     * @return bool|mixed
     */
    public function getUserByHxUUID($hxuuid) {
        $where['uuid'] = $hxuuid;
        $user_res = $this->field('uid')->where($where)->find();
        if($user_res) {
            $data = $this->user_mode->getUserByUid($user_res['uid']);
            return $data ? $data : false;
        }
        return false;
    }
}
