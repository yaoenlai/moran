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
 * 用户关注模型
 * @author zxq
 */
class FollowModel extends Model {
    /**
     * 数据库表名
     * @author zxq
     */
    protected $tableName = 'love_follow';

    /**
     * 自动验证规则
     * @author zxq
     */
    protected $_validate = array(
        array('uid', 'require', '用户ID必须', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
        array('follow_uid', 'require', '粉丝ID必须', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
    );

    /**
     * 自动完成规则
     * @author zxq
     */
    protected $_auto = array(
        array('create_time', 'time', self::MODEL_INSERT, 'function'),
        array('update_time', 'time', self::MODEL_BOTH, 'function'),
        array('status', 1, self::MODEL_INSERT, 'string'),
    );

    /**
     * 当天新粉丝数量
     * @param $int UID
     * @author zxq
     */
    public function newFansCount($uid = null) {
        if ($uid) {
            $map['uid'] = array('eq', $uid);
        } else {
            $map['uid'] = array('eq', is_login());
        }
        $map['status'] = array('eq', 1);
        $today = strtotime(date('Y-m-d', time())); //今天
        $map['create_time'] = array(
                                array('egt', $today),
                                array('lt', $today+86400)
                            );
        return $this->where($map)->count();
    }

    /**
     * 获取用户的粉丝数量
     * @param $int UID
     * @author zxq
     */
    public function fansCount($uid = null) {
        if ($uid) {
            $map['uid'] = array('eq', $uid);
        } else {
            $map['uid'] = array('eq', is_login());
        }
        $map['status'] = array('eq', 1);
        return $this->where($map)->count();
    }

    /**
     * 获取关注的用户数量
     * @param $int UID
     * @author zxq
     */
    public function followCount($uid = null) {
        if ($uid) {
            $map['follow_uid'] = array('eq', $uid);
        } else {
            $map['follow_uid'] = array('eq', is_login());
        }
        $map['status'] = array('eq', 1);
        return $this->where($map)->count();
    }

    /**
     * 获取收藏状态
     * @author zxq
     */
    public function get_follow_status($uid) {
        $con = array();
        $con['uid'] = $uid;
        $con['follow_uid'] = is_login();
        $con['status'] = 1;
        $result = $this->where($con)->find();
        return $result;
    }
}
