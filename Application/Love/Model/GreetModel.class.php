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
 * 个人用户模型
 * @author zxq
 */
class GreetModel extends Model {
    /**
     * 数据库表名
     * @author zxq
     */
    protected $tableName = 'love_greet';

    /**
     * 自动验证规则
     * @author zxq
     */
    protected $_validate = array(
        array('male', 'require', '请选择可否发给男生', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('female', 'require', '请选择可否发给女生', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('greet_type', 'require', '请选择招呼类型', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('greeting', 'require', '招呼内容不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
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
     * 随机获取一条信息
     * @greet_type 消息类型
     * @gender     可发给男，女
     */

    public function getGreetingRandom($greet_type = 1,$gender = 1){
        //获取类型为1的
        $map['greet_type'] = $greet_type==1?$greet_type:2;
        //性别
        if ($gender == 1) {
            $map['male'] = 1;
        }else{
            $map['female'] = 1;
        }
        $rel = $this->field('greeting')->where($map)->limit(1)->order('random()')->select();
        return $rel[0]['greeting'];
    }

}

