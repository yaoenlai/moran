<?php
// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------
namespace Weixin\Controller;
use Home\Controller\HomeController;
require_once dirname(dirname(__FILE__)).'/Util/Wechat/wechat.class.php';
/**
 * 默认控制器
 * @author zxq
 */
class UserBindController extends HomeController {
    /**
     * 默认方法
     * @author zxq
     */
    public function index() {
        // 判断判断是微信浏览器自动跳转登录页面
        if (\Common\Util\Device::isWeixin() && !(is_login())) {
            //加载微信SDK
            $options = array(
                'token'          => C('weixin_config.token'),           //填写你设定的key
                'encodingaeskey' => C('weixin_config.crypt'),           //填写加密用的EncodingAESKey
                'appid'          => C('weixin_config.appid'),           //填写高级调用功能的app id, 请在微信开发模式后台查询
                'appsecret'      => C('weixin_config.appsecret')        //填写高级调用功能的密钥
            );
            $wechat = new \Wechat($options);

            // 重定向至微信登录页面
            $callback_uri = U('login', null, null, true);  // 重要回调地址必须含有http
            $redirect_uri = $wechat->getOauthRedirect($callback_uri);
            redirect($redirect_uri);
        } else {
            $this->redirect('Weixin/Index/index');
        }
    }

    /**
     * 微信登录回调接口
     * @author zxq
     */
    public function login() {
        //加载微信SDK
        $options = array(
            'token'          => C('weixin_config.token'),           //填写你设定的key
            'encodingaeskey' => C('weixin_config.crypt'),           //填写加密用的EncodingAESKey
            'appid'          => C('weixin_config.appid'),           //填写高级调用功能的app id, 请在微信开发模式后台查询
            'appsecret'      => C('weixin_config.appsecret')        //填写高级调用功能的密钥
        );
        $wechat = new \Wechat($options);

        // 获取微信用户信息
        $user_token = $wechat->getOauthAccessToken();
        $weixin_user_info = $wechat->getOauthUserinfo($user_token['access_token'], $user_token['openid']);

        // 查询微信登录表是否已经有用户
        $con['status'] = 1;
        $con['openid'] = $weixin_user_info['openid'];
        $exist_user = D('UserBind')->where($con)->find();

        // 如果存在则直接自动登录否则跳转到绑定界面
        if ($exist_user) {
            $user_object = D('User/User');
            $corethink_user_info = $user_object->find($exist_user['uid']);
            if ($corethink_user_info) {
                $user_object->auto_login($corethink_user_info);
                redirect(cookie('forward') ? : C('HOME_PAGE'));
            } else {
                $this->error('该用户已禁用或不存在！');
            }
        } else {
            session('weixin_user_info', $weixin_user_info);
            $this->assign('weixin_user_info', $weixin_user_info);
            $this->assign('meta_title', "微信登录" );
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
        $weixin_user_info = session('weixin_user_info');
        $username = 'U'.time();
        $password = $weixin_user_info['openid'];

        // 构造注册数据
        $reg_data = array();
        $reg_data['user_type'] = 1;
        $reg_data['nickname']  = $weixin_user_info['nickname'];
        $reg_data['username']  = $username;
        $reg_data['password']  = $password;
        $reg_data['reg_type']  = 'weixin';
        $reg_data['avatar']    = $_POST['avatar'];
        $user_object = D('User/User');
        $user_data   = $user_object->create($reg_data);
        if($user_data){
            $uid = $user_object->add($user_data);
            if ($uid) {
                //新增微信登录账号
                $weixin_data['uid']    = $uid;
                $weixin_data['openid'] = $weixin_user_info['openid'];
                $weixin_login = D('UserBind');
                $weixin_data  = $weixin_login->create($weixin_data);
                if ($weixin_data) {
                    $result = $weixin_login->add($weixin_data);
                    if ($result) {
                        //登录用户
                        $user_info = D('Admin/User')->getUserInfo($uid);
                        if ($user_info) {
                            $uid = D('User/User')->auto_login($user_info);
                            session('weixin_user_info', null);
                            $this->success('注册成功', cookie('forward') ? : C('HOME_PAGE'));
                        } else {
                            $this->error('错误');
                        }
                    }
                } else {
                    $this->error('错误'.$weixin_login->getError());
                }
            } else {
                $this->error('注册失败');
            }
        } else {
            $this->error($user_object->getError());
        }
    }

    /**
     * 绑定用户
     * @author zxq
     */
    public function bind() {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $user_object = D('User/User');
        $uid = $user_object->login($username, $password);
        if($uid > 0){
            //新增微信登录账号
            $weixin_user_info = session('weixin_user_info');
            $weixin_data['uid']    = $uid;
            $weixin_data['openid'] = $weixin_user_info['openid'];
            $weixin_login = D('UserBind');
            $weixin_data  = $weixin_login->create($weixin_data);
            if ($weixin_data) {
                $result = $weixin_login->add($weixin_data);
                if ($result) {
                    session('weixin_user_info', null);
                    $this->success('微信账号绑定成功', cookie('forward') ? : C('HOME_PAGE'));
                }
            } else {
                $this->error('错误'.$weixin_login->getError());
            }
        } else {
            $this->error('绑定失败'.$user_object->getError()); // 绑定失败
        }
    }
}
