<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.corethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq <http://www.corethink.cn>
// +----------------------------------------------------------------------
namespace Addons\SyncLogin\Controller;
use Think\Hook;
use Home\Controller\AddonController;
require_once(dirname(dirname(__FILE__))."/ThinkSDK/ThinkOauth.class.php");
require_once(dirname(dirname(__FILE__))."/ThinkSDK/ThinkOauthInfo.class.php");
/**
 * 第三方登录控制器
 */
class LoginController extends AddonController {
    /**
     * 登录地址
     */
    public function login(){
        $type= I('get.type');
        empty($type) && $this->error('参数错误');
        $sns  = \ThinkOauth::getInstance($type); //加载ThinkOauth类并实例化一个对象
        redirect($sns->getRequestCodeURL()); //跳转到授权页面
    }

    /**
     * 登陆后回调地址
     */
    public function callback(){
        $code =  I('get.code');
        $type= I('get.type');
        $sns  = \ThinkOauth::getInstance($type);

        //腾讯微博需传递的额外参数
        $extend = null;
        if($type == 'tencent'){
            $extend = array('openid' => I('get.openid'), 'openkey' =>  I('get.openkey'));
        }

        $token = $sns->getAccessToken($code , $extend); //获取第三方Token
        $user_sns_info = \ThinkOauthInfo::$type($token); //获取第三方传递回来的用户信息
        $user_sync_info = D('Addons://SyncLogin/SyncLogin')->getUserByOpenidAndType($token['openid'], $type); //根据openid等参数查找同步登录表中的用户信息
        $user_sys_info = D('Admin/User')->find($user_sync_info ['uid']); //根据UID查找系统用户中是否有此用户
        if ($user_sync_info['uid'] && $user_sys_info['id'] && $user_sync_info['uid'] == $user_sys_info['id']) { //曾经绑定过
            D('Addons://SyncLogin/SyncLogin')->updateTokenByTokenAndType($token, $type);
            D('User/User')->auto_login($user_sys_info);
            redirect(Cookie('__forward__') ? : C('HOME_PAGE'));
        } else { //没绑定过，去注册页面
            session('token', $token);
            session('user_sns_info', $user_sns_info);
            $this->assign('user_sns_info', $user_sns_info);
            $this->assign('meta_title', "帐号绑定" );
            $this->display();
        }
    }

    /**
     * 创建新用户
     * @author zxq
     */
    public function register() {
        //上传头像，发现相同文件直接返回
        $upload_object = D('Admin/Upload');
        $con['url']    = $_POST['avatar'];
        $upload_exist  = $upload_object->where($con)->find();
        if ($upload_exist) {
            $_POST['avatar'] = $upload_exist['id'];
        } else {
            $upload_data['name'] = '第三方头像';
            $upload_data['url']  = $_POST['avatar'];
            $upload_data['ext']  = 'png';
            $upload_data['md5']  = md5_file($_POST['avatar']);
            $upload_data['sha1'] = sha1_file($_POST['avatar']);
            $upload_data_create  = $upload_object->create($upload_data);
            if ($upload_data_create) {
                $_POST['avatar'] = $upload_object->add($upload_data_create);
            } else {
                $this->error('头像信息存储错误'.$upload_object->getError());
            }
        }

        //注册用户
        $user_sns_info = session('user_sns_info');
        $username = $_POST['username'];
        $password = $user_sns_info['openid'];

        // 构造注册数据
        $reg_data = array();
        $reg_data['user_type'] = 1;
        $reg_data['nickname']  = $user_sns_info['name'];
        $reg_data['username']  = $username;
        $reg_data['password']  = $_POST['password'];
        $reg_data['reg_type']  = strtolower($user_sns_info['type']);
        $reg_data['avatar']    = $_POST['avatar'];
        $user_object = D('User/User');
        $user_data   = $user_object->create($reg_data);
        if($user_data){
            $uid = $user_object->add($user_data);
            if ($uid) {
                $sync_object = D('Addons://SyncLogin/SyncLogin');
                $result = $sync_object->update($uid);
                if ($result) {
                    //登录用户
                    $user_info = D('Admin/User')->getUserInfo($uid);
                    if ($user_info) {
                        $uid = D('User/User')->auto_login($user_info);
                        session('user_sns_info', null);
                        $this->success('注册成功', Cookie('__forward__') ? : C('HOME_PAGE'));
                    } else {
                        $this->error('错误');
                    }
                } else {
                    $this->error('错误'. $sync_object->getError());
                }
            } else {
                $this->error('注册失败');
            }
        } else {
            $this->error($user_object->getError());
        }
    }

    /**
     * 绑定本地帐号
     */
    public function bind(){
        $uid = is_login();
        if (!$uid) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $user_object = D('User/User');
            $uid = $user_object->login($username, $password);
        }
        if ($uid > 0) {
            //新增SNS登录账号
            $sobject = D('Addons://SyncLogin/SyncLogin');
            if ($sobject->update($uid)) {
                session('user_sns_info', null);
                $this->success('SNS账号绑定成功', Cookie('__forward__') ? : C('HOME_PAGE'));
            } else {
                $this->error('新增SNS登录账号失败'.$sobject->getError());
            }
        } else {
            $this->error('绑定失败'.$user_object->getError()); // 绑定失败
        }
    }

    /**
     * 取消绑定本地帐号
     */
    public function cancelbind($uid){
        $condition['uid'] = $uid;
        $condition['type'] = $_GET['type'];
        $ret = D('Addons://SyncLogin/SyncLogin')->where($condition)->delete();
        if($ret){
            $this->success('取消绑定成功');
        }else{
            $this->error('取消绑定失败');
        }
    }
}
