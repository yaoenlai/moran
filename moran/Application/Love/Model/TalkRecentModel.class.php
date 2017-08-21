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
 * 私信模型
 * @author zxq
 */
class TalkRecentModel extends Model {
    /**
     * 数据库表名
     * @author zxq
     */
    protected $tableName = 'love_talk_recent';

    /**
     * 自动验证规则
     * @author zxq
     */
    protected $_validate = array(
        array('uids','require','缺少UID', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('data_id','require','缺少消息ID', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    );

    /**
     * 自动完成规则
     * @author zxq
     */
    protected $_auto = array(
        array('create_time', 'time', self::MODEL_INSERT, 'function'),
        array('update_time', 'time', self::MODEL_BOTH, 'function'),
        array('sort', '0', self::MODEL_INSERT),
        array('status', '1', self::MODEL_INSERT),
    );

    /**
     * 查找后置操作
     * @author zxq
     */
    protected function _after_find(&$result, $options) {
        $result['user'] = D('Admin/User')->getUserInfo($result['from_uid']);
        $result['create_time_format'] = time_format($result['create_time']);
    }

    /**
     * 查找后置操作
     * @author zxq
     */
    protected function _after_select(&$result, $options) {
        foreach($result as &$record){
            $this->_after_find($record, $options);
        }
    }
}
