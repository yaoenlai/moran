<?php
// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------
namespace Weixin\Model;
use Think\Model;
/**
 * 公众号模型
 * @author zxq
 */
class CustomReplyModel extends Model{
    /**
     * 数据库真实表名
     * 一般为了数据库的整洁，同时又不影响Model和Controller的名称
     * 我们约定每个模块的数据表都加上相同的前缀，比如微信模块用weixin作为数据表前缀
     * @author zxq
     */
    protected $tableName = 'weixin_custom_reply';

    /**
     * 自动验证规则
     * @author zxq
     */
    protected $_validate = array(
        array('keyword', 'require', '关键词不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('reply_type', 'require', '回复类型不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
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
     * 自定义回复类型
     * @author zxq
     */
    public function reply_type($id) {
        $list['text']     = '文本回复';
        $list['material'] = '图文回复';
        $list['addon']    = '插件回复';
        return $id ? $list[$id] : $list;
    }
}