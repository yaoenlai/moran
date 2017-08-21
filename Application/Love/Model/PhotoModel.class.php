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
 * 用户类型模型
 * @author zxq
 */
class PhotoModel extends Model {
    /**
     * 数据库表名
     * @author zxq
     */
    protected $tableName = 'love_photo';

    /**
     * 自动验证规则
     * @author zxq
     */
    protected $_validate = array(
        //array('name', 'require', '类型名称不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        //array('name', '/^[\w]+$/', '名称必须是纯英文，不包含下划线、空格及其他字符', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        //array('title', 'require', '类型标题不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    );

    /**
     * 自动完成规则
     * @author zxq
     */
    protected $_auto = array(
        array('create_time', 'time', self::MODEL_INSERT, 'function'),
        array('update_time', 'time', self::MODEL_BOTH, 'function'),
        array('status', '1', self::MODEL_INSERT),
        array('phototype', '1', self::MODEL_INSERT),
    );
	
	/**
     * 查找后置操作
     * @author zxq
     */
    protected function _after_find(&$result, $options) {
        $result['photo_url'] = get_cover($result['upid'], 'avatar');
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
