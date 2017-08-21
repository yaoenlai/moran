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
 * 消息推送设备记录模型
 * @author zxq
 */
class MessagePushModel extends Model {
    /**
     * 数据库表名
     * @author zxq
     */
    protected $tableName = 'love_message_push';

    /**
     * 自动验证规则
     * @author zxq
     */
    protected $_validate = array(
        array('uid','require','UID必须填写', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
        array('token', '1,127', 'token长度为1-127个字符', self::MUST_VALIDATE, 'length', self::MODEL_BOTH),
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

    /**
     * OS类型
     * @author zxq
     */
    public function os_type($id) {
        $list[1] = 'iOS';
        $list[2] = 'Android';
        $list[3] = 'Windows';
        return $id ? $list[$id] : $list;
    }
}
