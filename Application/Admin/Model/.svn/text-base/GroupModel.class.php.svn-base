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
 * 部门模型
 * @author zxq
 */
class GroupModel extends Model {
    /**
     * 数据库表名
     * @author zxq
     */
    protected $tableName = 'admin_group';

    /**
     * 自动验证规则
     * @author zxq
     */
    protected $_validate = array(
        array('title', 'require', '部门名称不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('title', '1,32', '部门名称长度为1-32个字符', self::EXISTS_VALIDATE, 'length', self::MODEL_BOTH),
        array('title', '', '部门名称已经存在', self::VALUE_VALIDATE, 'unique', self::MODEL_BOTH),
        array('menu_auth', 'require', '权限不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
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
     * 检查部门功能权限
     * @author zxq
     */
    public function checkMenuAuth() {
        $current_menu = D('Admin/Module')->getCurrentMenu(); // 当前菜单
        $user_group = D('Admin/Access')->getFieldByUid(session('user_auth.uid'), 'group');  // 获得当前登录用户信息
        if ($user_group !== '1') {
            $group_info = $this->find($user_group);
            // 获得当前登录用户所属部门的权限列表
            $group_auth = json_decode($group_info['menu_auth'], true);
            if (in_array($current_menu['id'], $group_auth[MODULE_NAME])) {
                return true;
            }
        } else {
            return true;  // 超级管理员无需验证
        }
        return false;
    }
	
	/**
     * 获取&检测部门功能权限菜单
     * @author ieras <ieras@qq.com>
     */
    public function getMenuAuth() {
        $current_menu = D('Admin/Module')->getCurrentMenu(); // 当前菜单
        $user_group = D('Admin/Access')->getFieldByUid(session('user_auth.uid'), 'group');  // 获得当前登录用户信息
        if ($user_group !== '1') {
        	$Info['isAdmin'] = '-1';
            $group_info = $this->find($user_group);
            // 获得当前登录用户所属部门的权限列表
            $group_auth = json_decode($group_info['menu_auth'], true);
			$Info['groupTitle'] = $group_info['title'];
            if (in_array($current_menu['id'], $group_auth[MODULE_NAME])) {
                $Info['isAuth'] = '1';
            }else{
            	$Info['isAuth'] = '-1';
            }
			$Info['groupAuth'] = $group_auth;
        } else {
            $Info['isAdmin'] = '1';  // 超级管理员无需验证
        }
        return $Info;
    }
}
