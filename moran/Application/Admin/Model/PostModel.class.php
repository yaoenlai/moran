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
 * 文章模型
 * @author zxq
 */
class PostModel extends Model {
    /**
     * 数据库真实表名
     * 一般为了数据库的整洁，同时又不影响Model和Controller的名称
     * 我们约定每个模块的数据表都加上相同的前缀，比如微信模块用weixin作为数据表前缀
     * @author zxq
     */
    protected $tableName = 'admin_post';

    /**
     * 自动验证规则
     * @author zxq
     */
    protected $_validate = array(
        array('title', 'require', '标题不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('title', '1,80', '标题长度为1-80个字符', self::EXISTS_VALIDATE, 'length'),
        array('title', '', '标题已经存在', self::VALUE_VALIDATE, 'unique', self::MODEL_BOTH),
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
     * 查找后置操作
     * @author zxq
     */
    protected function _after_find(&$result, $options) {
       if ($result['cover']) {
           $result['cover_url'] = get_cover($result['cover'], 'default');
       }
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

    /**
     * 获取列表
     * @author zxq
     */
    public function getList($cid, $limit = 10, $page = 1, $order = null, $map = null) {
        $con["status"] = array("eq", '1');
        $con["cid"]    = array("eq", $cid);
        if ($map) {
            $map = array_merge($con, $map);
        }
        if (!$order) {
            $order = 'sort desc, id desc';
        }
        $list = $this->page($page, $limit)
                          ->order($order)
                          ->where($map)
                          ->select();

        return $list;
    }
}
