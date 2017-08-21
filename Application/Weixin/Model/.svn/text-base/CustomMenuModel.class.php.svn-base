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
class CustomMenuModel extends Model {
    /**
     * 数据库真实表名
     * 一般为了数据库的整洁，同时又不影响Model和Controller的名称
     * 我们约定每个模块的数据表都加上相同的前缀，比如微信模块用weixin作为数据表前缀
     * @author zxq
     */
    protected $tableName = 'weixin_custom_menu';

    /**
     * 自动验证规则
     * @author zxq
     */
    protected $_validate = array(
        array('name', 'require', '菜单名称不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('type', 'require', '菜单事件类型不能为空', self::VALUE_VALIDATE, 'regex', self::MODEL_BOTH),
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
     * 自定义菜单类型
     * @author zxq
     */
    public function menu_type($id) {
        $list['none']  = '无事件的一级菜单';
        $list['click'] = '点击触发自动回复';
        $list['view']  = '外链URL地址';
        //$list['scancode_push'] = '扫码推事件';
        //$list['scancode_waitmsg'] = '扫码推事件且弹出“消息接收中”提示框';
        //$list['pic_sysphoto'] = '弹出系统拍照发图';
        //$list['pic_photo_or_album'] = '弹出拍照或者相册发图';
        //$list['pic_weixin'] = '弹出微信相册发图器';
        //$list['location_select'] = '弹出地理位置选择器';
        //$list['vimedia_idew'] = '下发消息（除文本消息）';
        //$list['view_limited'] = '跳转图文消息URL';
        return $id ? $list[$id] : $list;
    }
}