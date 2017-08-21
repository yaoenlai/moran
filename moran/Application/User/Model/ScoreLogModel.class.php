<?php
// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------
namespace User\Model;
use Think\Model;
/**
 * 用户积分模型
 * @author zxq
 */
class ScoreLogModel extends Model {
    /**
     * 数据库表名
     * @author zxq
     */
    protected $tableName = 'love_score_log';

    /**
     * 自动验证规则
     * @author zxq
     */
    protected $_validate = array(
        array('uid','require','UID必须填写', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('uid', 'number', 'UID必须数字', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('type', 'number', '变动方式必须数字', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('type','require','变动方式必须填写', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('score','require','变动数量必须填写', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('score', 'number', '变动数量必须数字', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('message','require','变动说明必须填写', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('message', '1,255', '变动说明长度为1-255个字符', self::EXISTS_VALIDATE, 'length', self::MODEL_BOTH),
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
     * 积分变动类型
     * @author zxq
     */
    public function change_type($id) {
        $list[1]  = '增加';
        $list[2]  = '减少';
        return $id ? $list[$id] : $list;
    }

    /**
     * 积分变动
     * @author zxq
     */
    public function changeScore($type, $uid, $score, $message, $field='score') {
        $data['type']    = $type;
        $data['uid']     = $uid;
        $data['score']   = $score;
        $data['message'] = $message;
        $data = $this->create($data);
        if ($data) {
            $map['id'] = $data['uid'];
            switch ($data['type']) {
                case 1:
                    $result = D('User/User')->where($map)->setInc($field, $data['score']);
                    break;
                case 2:
                    $result = D('User/User')->where($map)->setDec($field, $data['score']);
                    break;
            }
            if ($result) {
                $result = $this->add($data);
                return true;
            }
        } else {
            return $this->getError();
        }
    }
}
