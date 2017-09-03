<?php

// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------

namespace Love\Api;

use Love\Api\Api;
use Think\Exception;
use Think\Think;
use Common\Common\MessageModule;

/**
 * 游戏sdk登录接口
 * @author zxq
 */
class UserApi extends Api {

    /**
     * 默认方法 跳转到v1版
     * @author zxq
     */
    public function index() {
        echo "用户相关接口";
    }

    /**
     * 游戏sdk注册接口
     * http://www.isgcn.com/api/love/user/register.api
     * 接口必传gid 或者 地址必配gid 或者 gid放接口参数
     * @author zxq
     */
    public function register() {
        if ($this->verifySign()) {
            $post = $this->requestData;
            //try{
            $nickName = D('Admin/SysNicks')->randomNickname($post['gender']); //随机获取一个昵称
            $user_object = D('Love/User'); //实例化pc用户Db
            $user_object->startTrans();
            $gender = ($post['gender'] == '-1') ? '女士' : '男士';
            $post['nickname'] = $nickName; //用户名
            $post['username'] = 'yh' . substr(time(), 2, 10); //用户名
            $post['password'] = 'mm' . rand(pow(10, (8 - 1)), pow(10, 8) - 1); //生成八位随机密码
            $post['user_type'] = 1;
            $post['reg_type'] = 'mobile';
            //注册 环信账号
            $user_object_model = $user_object->create($post);

            if (!$user_object_model) {
                $this->ajaxReturn(returnInfo('-5', $user_object->getError(), null, $this->infoType), $this->returnType);
                $user_object->rollback();
            }
            $userId = $user_object->add(); //成功返回自增id 例如：21

            $hxUser = $this->huanxin->createUser($post['username'], $post['password'], $post['nickname']);

            $hxUser_object = D('Love/HxUser');
            $hxData['uid'] = $userId;
            $hxData['uuid'] = $hxUser['entities'][0]['uuid'];
            $hxData['type'] = $hxUser['entities'][0]['type'];
            $hxData['username'] = $hxUser['entities'][0]['username'];
            $hxData['nickname'] = $nickName;
            $hxData['status'] = 1;
            $hxData['password'] = $post['password'];
            $hxData['create_time'] = $hxUser['entities'][0]['created'];
            $hxData['update_time'] = $hxUser['entities'][0]['modified'];
            $hxUser_object_model = $hxUser_object->create($hxData);
            $hxUser_object->add();
            if (!$hxUser_object_model) {
                $this->ajaxReturn(returnInfo('-5', $hxUser_object->getError(), null, $this->infoType), $this->returnType);
                $user_object->rollback();
            }

            $love_attr = D('Love/Attr');
            $attr['uid'] = $userId;
            $attr['privacy'] = 1;
            $love_attr_model = $love_attr->create($attr);
            if (!$love_attr_model) {
                $this->ajaxReturn(returnInfo('-5', $love_attr->getError(), null, $this->infoType), $this->returnType);
                $user_object->rollback();
            }
            $love_attr->add();
            $love_profile = D('Love/Profile');
            $attr['uid'] = $userId;
            $attr['sid'] = $this->sid;
            $love_profile_model = $love_profile->create($attr);

            if (!$love_profile_model) {
                $this->ajaxReturn(returnInfo('-5', $love_profile->getError(), null, $this->infoType), $this->returnType);
                $user_object->rollback();
            }
            $love_profile->add();
            $status = $user_object->commit();
            $data = array(
                'username' => $post['username'],
                'uid' => $userId,
                'pass' => $post['password'],
                'name' => $post['nickname'],
                'hx_uid' => $hxData['uuid'],
                'hx_pass' => $post['password']
            );

            //随机一个机器人UID
            $robotWhere['gender'] = ($post['gender'] == '-1') ? '1' : '-1';
            $robotWhere['id'] = array('BETWEEN', '5,4961');
            $robotInfo = D('Love/User')->where($robotWhere)->order('RAND() desc')->limit(1)->find();

            //分配机器人给该用户
            $greetrobot_object = D('Love/Greetrobot');
            $robot['uid'] = $userId;
            $robot['robot_uid'] = $robotInfo['id'];
            $rootres = $greetrobot_object->create($robot, 1); //dump($rootres);
            if ($rootres) {
                $greetrobot_object->add(); //分配机器人完成
            }

            //写入用户扩展信息
            $userext_object = D('Love/Userext');
            $userext['uid'] = $userId;
            $userext['sid'] = $post['sid'];
            $userextres = $userext_object->create($userext, 1); //dump($rootres);
            if ($userextres) {
                $userext_object->add(); //分配机器人完成
            }
            
            $result = $this->wrap_user_info($data, TRUE);
            print_r($result);exit();
            $this->ajaxReturn(returnInfo('1', '注册成功!', $data, $this->infoType), $this->returnType);
        } else {
            $this->ajaxReturn(returnInfo('-3', '验签失败!', null, $this->infoType), $this->returnType);
        }
    }

    /**
     * 游戏sdk登录接口
     * http://www.isgcn.com/api/game/user/login.api
     * 接口必传gid 或者 地址必配gid 或者 gid放接口参数
     * @author zxq
     */
    public function login() {
        if ($this->verifySign()) {
            $post = $this->requestData;
            $object = D('Love/User');
            $result = $object->login(trim($post['username']), trim($post['password']), true);
            $loginData['update_time'] = time();
            D('Love/User')->where("id={$result['id']}")->save($loginData);
            //file_put_contents('/tmp/sign.txt', microDate("y-m-d H:i:s.x").' 查询结果:'.$post['username'].$post['password'].json_encode($result)."\r\n\r\n",FILE_APPEND);
            if ($result['id'] > 0) {
                //男士用户，且未付费用户加入系统聊天队列,暂定 user_type == 1 为未付费用户
                // if login success, ok let's join the share queue and wait for deal with this queue
                // 环信表查询该用户的 环信ID
                $hx_model = D('Love/HxUser');
                $hx_r = $hx_model->huanxinInfo($result['id']);
                if ($hx_r != false && !empty($hx_r['uuid'])) {
                    $message_data = [
                        'uid' => $result['id'],
                        'username' => $result['username'],
                        'gender' => $result['gender'],
                        'hx_uid' => $hx_r['uuid'],
                    ];
                    $messageModule = new MessageModule($this->huanxin);
                    $messageModule->joinMessageQueue($message_data);
                    $messageModule->processingMessage();
                }
                $returnData['uid'] = $result['id'];
                $returnData['username'] = $result['username'];
                $returnData['gender'] = $result['gender'];
                $returnData['avatar_url'] = $result['avatar_url'];
                $returnData['nickname'] = $result['nickname'];
                $returnData['password'] = $hx_r["password"];
                $this->ajaxReturn(returnInfo('1', '登录成功!', $returnData, $this->infoType), $this->returnType);
            } else {
                //file_put_contents('/tmp/sign.txt', microDate("y-m-d H:i:s.x").' 错误数据:'.json_encode($post)."\r\n\r\n",FILE_APPEND);
                //file_put_contents('/tmp/sign.txt', microDate("y-m-d H:i:s.x").' 查询cuowu:'.json_encode($result)."\r\n\r\n",FILE_APPEND);
                $this->ajaxReturn(returnInfo('-1', '账号不存在', null, $this->infoType), $this->returnType);
            }
        } else {
            $this->ajaxReturn(returnInfo('-3', '验签失败!', null, $this->infoType), $this->returnType);
        }
    }

    /**
     * 游戏服务端验证聚合用户Token接口
     * http://www.isgcn.com/api/game/user/verify.api
     * @author zxq
     */
    public function verify() {
        if ($this->verifySign('secret')) {
            $data = $this->requestData;
            //验证Token
            $token = $data['token'];
            unset($data['token']); //记录并注销原始Token
            $iToken = $this->createSign($data, 'secret'); //计算Token
            if ($token == $iToken) {//验证Token
                $object = D('User/User');
                $map['id'] = $data['userID'];
                $map['token'] = $token;
                //通过接口传递过来的参数，查询数据库中匹配的数据
                $gpuserInfo = $object->where($map)->find();
                //dump($gpuserInfo);
                if (!empty($gpuserInfo)) {
                    unset($data['sign']);
                    $this->ajaxReturn(returnInfo('1', '认证成功!', $data, $this->infoType), $this->returnType);
                } else {
                    $this->ajaxReturn(returnInfo('-7', '认证失败!', null, $this->infoType), $this->returnType);
                }
            } else {
                $this->ajaxReturn(returnInfo('-3', 'Token验签失败!', null, $this->infoType), $this->returnType);
            }
        } else {
            $this->ajaxReturn(returnInfo('-3', '验签失败!', null, $this->infoType), $this->returnType);
        }
    }

    /**
     * 聚合客户端注销登录接口
     * @param array $data
     * array{
     *  	userID 	用户id
     * 		。。。
     *     }
     * http://www.isgcn.com/api/game/user/logOut.api
     * @author zxq
     */
    public function logOut() {
        if ($this->verifySign()) {
            $data = $this->requestData;
            $rsData['aggid'] = 1;
            $rsData['guid'] = $data['userID']; //退出只需要知道guid(客户端当作guid为uid)即可
            $result = D('Game/LoginLog')->createUserLog($rsData, 2);
            if ($result) {
                $this->ajaxReturn(returnInfo('1', '注销成功!', null, $this->infoType), $this->returnType);
            } else {
                $this->ajaxReturn(returnInfo('-8', '注销失败!', null, $this->infoType), $this->returnType);
            }
        } else {
            $this->ajaxReturn(returnInfo('-3', '验签失败!', null, $this->infoType), $this->returnType);
        }
    }

    /**
     *
     * 用户头像上传
     *
     */
    public function avatar() {

        $post = $this->requestData;
        if (empty($post['uid'])) {
            $this->ajaxReturn(returnInfo('1', '必传参数 UID 不能为空', null, $this->infoType), $this->returnType);
        }
        $where['id'] = $post['uid'];
        $user_model = D('Admin/User')->where($where)->find();
        if (!$user_model) {
            $this->ajaxReturn(returnInfo('1', '用户未找到', null, $this->infoType), $this->returnType);
        }
        $data = D('Admin/Upload')->upload($_FILES);
        file_put_contents('/tmp/avatar.txt', microDate("y-m-d H:i:s.x") . ' 上传结果:' . createLinkstring($data) . "\r\n\r\n", FILE_APPEND);
        if ($data['error'] > 0) {
            $this->ajaxReturn(returnInfo('-3', $data['message'], null, $this->infoType), $this->returnType);
        } else {
            D('Admin/User')->where($where)->save(['avatar' => $data['id']]);
            $this->ajaxReturn(returnInfo('1', '上传头像成功!', $data, $this->infoType), $this->returnType);
        }

        exit;
    }

    /**
     *
     * 获取用户信息通过环信hxuuid
     *
     */
    public function userByHxId() {

        $post = $this->requestData;
        if (empty($post['hxuuids'])) {
            $this->ajaxReturn(returnInfo('1', '必传参数 UID 不能为空', null, $this->infoType), $this->returnType);
        }
        $hx_model = D('Love/HxUser');
        $response_data = [];
        $hxuuids = explode(',', $post['hxuuids']);
        $hxuuid_count = count($hxuuids);
        if ($hxuuid_count == 1) {
            $hxuuid = $post['hxuuids'];
            $res = $hx_model->getUserByHxUUID($hxuuid);
            if ($res != false) {
                $response_data[$hxuuid] = $res;
            } else {
                $response_data[$hxuuid] = null;
            }
        } else {
            // 未能优化， 环信 uuid 不存在的情况，先循环查询，返回以前端传递的值作为返回的key
            foreach ($hxuuids as $v) {
                $res = $hx_model->getUserByHxUUID($v);
                if ($res != false) {
                    $response_data[$v] = $res;
                } else {
                    $response_data[$v] = null;
                }
            }
        }
        $this->ajaxReturn(returnInfo('1', '已经获取用户信息!', $response_data, $this->infoType), $this->returnType);
    }

    /**
     * vip用户信息
     * ps:该功能开发时需要有后台编辑用户协议功能
     * http://www.isgcn.com/api/game/user/Agreement.api
     * @author zxq
     */
    public function vipInfo() {
        if ($this->verifySign()) {
            $post = $this->requestData;
            $vipInfo = D('Love/Vip')->find($post['uid']);
            $onTime = time();
            $data['isVip'] = 0;
            if ($vipInfo['status'] == 1 and ( $onTime > $vipInfo['startdate']) and ( $onTime < $vipInfo['enddate'])) {
                // 我是vip
                $data['isVip'] = 1;
            } else {
                //omg 我vip到期了
                $siteInfo = D('Love/Site')->find($post['sid']);
                if ($siteInfo['isfree'] == 1)//1 免费 0 否
                    $data['isVip'] = 1;
            }
            if ($data['isVip'] == 1) {
                $data['talkVip'] = C('TMPL_PARSE_STRING.__IMG__') . '/vip_talk.png'; //聊天位置vip图片
                $data['viplevel'] = $vipInfo['viplevel']; //VIP等级
                $data['startdate'] = $vipInfo['startdate']; //开始时间
                $data['enddate'] = $vipInfo['enddate']; //结束时间
                $data['note'] = $vipInfo['note']; //备注(最后一次购买的套餐)
                $data['dataline'] = $vipInfo['dataline']; //vip在线时长
                $data['money'] = $vipInfo['money']; //用户总消费
                $data['package_id'] = $vipInfo['package_id']; //最后一次购买的套餐
                $data['create_time'] = $vipInfo['create_time']; //初次购买vip时间
                $data['update_time'] = $vipInfo['update_time']; //最后一次购买vip时间
                $code = '1';
                $msg = '权限通过!';
            } else {
                $code = '-1';
                $msg = '权限不足!';
            }
            //dump($vipInfo);exit;
            $this->ajaxReturn(returnInfo($code, $msg, $data, $this->infoType), $this->returnType);
        } else {
            $this->ajaxReturn(returnInfo('-3', '验签失败!', null, $this->infoType), $this->returnType);
        }
        exit;
    }

    /**
     *
     * 用相册上传
     *
     */
    public function photo() {
        $post = $this->requestData;
        if (empty($post['uid'])) {
            $this->ajaxReturn(returnInfo('-1', '必传参数 UID 不能为空', null, $this->infoType), $this->returnType);
        }
        $where['id'] = $post['uid'];
        $user_model = D('Love/User')->where($where)->find();
        if (!$user_model) {
            $this->ajaxReturn(returnInfo('-1', '用户未找到', null, $this->infoType), $this->returnType);
        }
        $where['uid'] = $post['uid'];
        $where['sid'] = $post['sid'];
        $count = D('Love/Photo')->where($where)->order('id desc')->count();
        if ($count > 8) {
            $this->ajaxReturn(returnInfo('-1', '用户相册最多9张', null, $this->infoType), $this->returnType);
        }
        $data = D('Admin/Upload')->upload($_FILES);
        file_put_contents('/tmp/avatar.txt', microDate("y-m-d H:i:s.x") . ' 上传结果:' . createLinkstring($data) . "\r\n\r\n", FILE_APPEND);
        if ($data['error'] > 0) {
            $this->ajaxReturn(returnInfo('-3', $data['message'], null, $this->infoType), $this->returnType);
        } else {
            $object = D('Love/Photo'); //->where($where)->save(['avatar' => $data['id']]);
            $photoData = array(
                'sid' => $post['sid'],
                'uid' => $post['uid'],
                'upid' => $data['id'],
            );
            $res = $object->create($photoData);
            if ($res) {
                $object->add();
                $this->ajaxReturn(returnInfo('1', '上传相片成功!', $data, $this->infoType), $this->returnType);
            } else {
                $this->ajaxReturn(returnInfo('-5', $data['message'], $object->getError(), $this->infoType), $this->returnType);
            }
        }
    }

    /**
     *
     * 我的相册
     *
     */
    public function myPhoto() {
        $post = $this->requestData;
        if (empty($post['uid'])) {
            $this->ajaxReturn(returnInfo('-1', '必传参数 UID 不能为空', null, $this->infoType), $this->returnType);
        }
        $page = $post['page']; //页码
        $offset = $post['offset']; //张数
        $where['uid'] = $post['uid'];
        $where['sid'] = $post['sid'];
        $count = D('Love/Photo')->where($where)->order('id desc')->count();

        $total = ceil($count / $offset); //总页数  = 总条数/条数
        $page = (abs($page) - 1) * $offset; //从$page开始
        $data = D('Love/Photo')->where($where)->limit($page, $offset)->order('id desc')->select();
        if (!empty($data)) {
            $return['data'] = $data;
            $return['total'] = $total;
            $this->ajaxReturn(returnInfo('1', '获取相册成功!', $return, $this->infoType), $this->returnType);
        } else {
            $this->ajaxReturn(returnInfo('-1', '没有照片啦！', null, $this->infoType), $this->returnType);
        }
    }

    /**
     * 修改密码
     * http://www.isgcn.com/api/love/user/resetPass.api
     */
    public function resetPass() {
        if ($this->verifySign()) {
            $post = $this->requestData;
            $deviceResult = $this->checkDevice();
            if ($deviceResult['code'] != 1) {
                $this->ajaxReturn(returnInfo($deviceResult['code'], $deviceResult['msg'], null, $this->infoType), $this->returnType);
            } else {
                $deviceInfo = $deviceResult['data'];
            }
            $userModel = D('User/User');
            if (empty($post['uid']))
                $this->ajaxReturn(returnInfo('-24', 'uid不能为空!', null, $this->infoType), $this->returnType);

            $validate = array(
                array('password', 'require', '请填写旧密码', 1, 'regex'),
                array('newPassword', '6,30', '密码长度为6-30位', 1, 'length'),
                array('newPassword', '/(?!^(\d+|[a-zA-Z]+|[~!@#$%^&*()_+{}:"<>?\-=[\];\',.\/]+)$)^[\w~!@#$%^&*()_+{}:"<>?\-=[\];\',.\/]+$/', '密码至少由数字、字符、特殊字符三种中的两种组成', 1, 'regex'),
            );
            $userModel->setProperty("_validate", $validate);
            $data = array(
                'password' => $post['password'],
                'newPassword' => $post['newPassword'],
            );
            $res = $userModel->validate($validate)->create($data);
            if ($res) {
                $password = user_md5($post['password']);
                $newpassword = user_md5($post['newPassword']);
                if ($password == get_user_info($post['uid'], 'password')) {
                    $result = $userModel->where(array('id' => $post['uid']))
                            ->setField('password', $newpassword);
                    if ($result) {
                        $this->ajaxReturn(returnInfo('1', '密码修改成功', null, $this->infoType), $this->returnType);
                    } else {
                        $this->ajaxReturn(returnInfo('-26', '密码修改失败' . $userModel->getError(), null, $this->infoType), $this->returnType);
                    }
                } else {
                    $this->ajaxReturn(returnInfo('-25', '旧密码错误' . $userModel->getError(), null, $this->infoType), $this->returnType);
                }
            } else {
                $this->ajaxReturn(returnInfo('-7', '错误：' . $userModel->getError(), null, $this->infoType), $this->returnType);
            }
        } else {
            $this->ajaxReturn(returnInfo('-3', '验签失败!', null, $this->infoType), $this->returnType);
        }
        exit;
    }

    /**
     * 修改邮箱
     * http://www.isgcn.com/api/game/user/resetEmail.api
     */
    public function resetEmail() {
        if ($this->verifySign()) {
            $post = $this->requestData;
            $deviceResult = $this->checkDevice();
            if ($deviceResult['code'] != 1) {
                $this->ajaxReturn(returnInfo($deviceResult['code'], $deviceResult['msg'], null, $this->infoType), $this->returnType);
            } else {
                $deviceInfo = $deviceResult['data'];
            }

            if (empty($post['userID']))
                $this->ajaxReturn(returnInfo('-24', 'id不能为空!', null, $this->infoType), $this->returnType);

            $userInfo = D('User/User')->detail($post['userID']);

            if ($userInfo['email'] == $post['email']) {
                //验证链接
                $encry = new \Think\Crypt();

                $encypt_str = $encry->encrypt(json_encode(array('uid' => $post['userID'], 'email' => $post['email'], 'time' => time())), 'yongshihuyu');
                $url = 'http://' . $_SERVER['HTTP_HOST'] . U('User/User/resetEmail', array('token' => $encypt_str));
                $mail_data['receiver'] = $post['email'];
                $mail_data['title'] = '邮箱修改' . '｜' . C('WEB_SITE_TITLE');
                ;
                //隐藏部分邮箱名
                $email_array = explode("@", $post['email']);
                $prevfix = (strlen($email_array[0]) < 4) ? "" : substr($post['email'], 0, 3); //邮箱前缀
                $count = 0;
                $str_mail = preg_replace('/([\d\w+_-]{0,100})@/', '***@', $post['email'], -1, $count);
                $rs_emial = $prevfix . $str_mail;
                // $mail_data['content'] = "
                //     <div class='emailbox' style='max-width: 800px; margin:0 auto; padding:20px; box-sizing: border-box; position: relative; margin-top:30px;'>
                //         <div class='main' style='margin-left:32px;'>
                //             <p class='name' style='color:#333;font-family: Microsoft Yahei;word-wrap: break-word;'>亲爱的" . C('WEB_SITE_TITLE') . "玩家<span style='color:#e60012;'>(" . $rs_emial . ")</span>：</p>
                //             <p style='color:#333;font-family: Microsoft Yahei;word-wrap: break-word;'>您好！</p>
                //             <p style='color:#333;font-family: Microsoft Yahei;word-wrap: break-word;'>您现在正在申请通行证邮箱修改，请点击下面链接完成操作：</p>
                //             <a href=" . $url . " style='background-color: #009E94; color: #fff; display: inline-block; height: 32px; line-height: 32px; margin: 0 15px 0 0; padding: 0 15px; text-decoration: none;'>修改邮箱</a>
                //             <p style='color:#333;font-family: Microsoft Yahei;word-wrap: break-word;'>如果上述文字点击无效，请把下面网页地址复制到浏览器地址栏中打开：</p>
                //             <a href=" . $url . " style='color:#428bca;'>" . $url . "</a>
                //             <p style='color:#333;font-family: Microsoft Yahei;word-wrap: break-word;'>如果您没有申请重置您勇士互娱通行证的邮箱，请忽略此邮件。</p>
                //             <p class='smright' style='color:#333;font-family: Microsoft Yahei;word-wrap: break-word; text-align: right;margin:5px;'>" . C('WEB_SITE_TITLE') . "</p>
                //             <p class='smright' style='color:#333;font-family: Microsoft Yahei;word-wrap: break-word; text-align: right;margin:5px;  '>" . date('Y年m月d日', time()) . "</p>
                //         </div>
                //         <div class='foot' style='background-color: #f9f9f9; height:80px; margin-top:20px; text-indent: 32px;'>
                //             <p class='info' style='margin:5px; color:#777; font-size:14px;padding-top:20px;'>本邮件由系统自动发送，请勿直接回复！如有任何疑问，请联系我们的客服人员。</p>
                //         </div>
                //     </div>
                // ";


                $mail_data['content'] = "<div style='width:100%;height:100%;background-color:#ecf0f5;padding:100px 0'><div style='border:2px solid #ccc;max-width:450px;padding:30px;border-radius: 15px;margin:0 auto;font-size:14px;font-family:Microsoft Yahei;line-height: 30px; color:#444; text-align: justify;background-color:#fff;'>
                    <p style='padding:0;margin:0;'><span>" . I('post.manager') . "</span>亲爱的用户，您好：</p>
                    <p style='padding:0;margin:0;margin-top:10px;'>您现在正在申请通行证邮箱修改，请点击下面链接完成操作：</p>
                    <p style='padding:0;margin:0;margin-top:10px;'><a style='color:#428BCA' href=" . $url . " target='_blank'>点击修改邮箱</a></p>
                    <p style='padding:0;margin:0;margin-top:10px;'>如果上述文字点击无效，请把下面网页地址复制到浏览器地址栏中打开：</p>
                    <p style='padding:0;margin:0;margin-top:10px;'><a style='color:#428BCA' href=" . $url . " target='_blank'>" . $url . "</a></p>
                    <p style='padding:0;margin:0;margin-top:10px;'>如果您没有申请重置您勇士互娱通行证的邮箱，请忽略此邮件。</p>
                    <p style='padding:0;margin:0;margin-top: 20px;'>游戏联运平台运营团队</p>
                    <p style='padding:0;margin:0'>" . date('Y-m-d H:i') . "</p>
                </div><p style='font-size: 14px;margin: 0 auto;color: #666;padding-top: 0px;max-width: 500px;margin: 0 auto;text-align: right;'>此邮件为系统邮件，请勿直接回复</p></div>";
                //拼接邮件内容信息结束
                //发送邮件
                $result = D('Addons://Email/Email')->send($mail_data);
                if ($result) {
                    $this->ajaxReturn(returnInfo('1', '邮箱发送成功!', null, $this->infoType), $this->returnType);
                } else {
                    $this->ajaxReturn(returnInfo('-5', '邮箱发送失败，请重新操作!', null, $this->infoType), $this->returnType);
                }
            } else {
                $this->ajaxReturn(returnInfo('-3', 'email不正确!', null, $this->infoType), $this->returnType);
            }
        } else {
            $this->ajaxReturn(returnInfo('-3', '验签失败!', null, $this->infoType), $this->returnType);
        }
        exit;
    }

    /**
     * 找回密码
     * http://www.isgcn.com/api/game/user/resetPassEmail.api
     * @param array  传递需要的参数
     * @return
     */
    public function resetPassEmail() {
        if ($this->verifySign()) {
            $post = $this->requestData;
            $deviceResult = $this->checkDevice();
            if ($deviceResult['code'] != 1) {
                $this->ajaxReturn(returnInfo($deviceResult['code'], $deviceResult['msg'], null, $this->infoType), $this->returnType);
            } else {
                $deviceInfo = $deviceResult['data'];
            }

            if (empty($post['email']))
                $this->ajaxReturn(returnInfo('-27', '邮箱不能为空!', null, $this->infoType), $this->returnType);
            $userInfo = D('User/User')->where(array('email' => $post['email']))->find();

            if (!empty($userInfo)) {
                //验证链接
                $encry = new \Think\Crypt();

                $encypt_str = $encry->encrypt(json_encode(array('email' => $userInfo['email'], 'time' => time())), 'yongshihuyu');
                $url = 'http://' . $_SERVER['HTTP_HOST'] . U('User/User/resetPassEmail', array('token' => $encypt_str));
                $mail_data['receiver'] = $userInfo['email'];
                $mail_data['title'] = '找回密码' . '｜' . C('WEB_SITE_TITLE');
                //隐藏部分邮箱名
                $email_array = explode("@", $userInfo['email']);
                $prevfix = (strlen($email_array[0]) < 4) ? "" : substr($userInfo['email'], 0, 3); //邮箱前缀
                $count = 0;
                $str_mail = preg_replace('/([\d\w+_-]{0,100})@/', '***@', $userInfo['email'], -1, $count);
                $rs_emial = $prevfix . $str_mail;

                // $mail_data['content'] = "
                //     <div class='emailbox' style='max-width: 800px; margin:0 auto; padding:20px; box-sizing: border-box; position: relative; margin-top:30px;'>
                //         <div class='main' style='margin-left:32px;'>
                //             <p class='name' style='color:#333;font-family: Microsoft Yahei;word-wrap: break-word;'>亲爱的" . C('WEB_SITE_TITLE') . "玩家<span style='color:#e60012;'>(" . $rs_emial . ")</span>：</p>
                //             <p style='color:#333;font-family: Microsoft Yahei;word-wrap: break-word;'>您好！</p>
                //             <p style='color:#333;font-family: Microsoft Yahei;word-wrap: break-word;'>您现在正在申请通行证找回密码操作，请点击下面链接完成操作：</p>
                //             <a href=" . $url . " style='background-color: #009E94; color: #fff; display: inline-block; height: 32px; line-height: 32px; margin: 0 15px 0 0; padding: 0 15px; text-decoration: none;'>找回密码</a>
                //             <p style='color:#333;font-family: Microsoft Yahei;word-wrap: break-word;'>如果上述文字点击无效，请把下面网页地址复制到浏览器地址栏中打开：</p>
                //             <a href=" . $url . " style='color:#428bca;'>" . $url . "</a>
                //             <p style='color:#333;font-family: Microsoft Yahei;word-wrap: break-word;'>如果您没有进行找回密码操作，请忽略此邮件。</p>
                //             <p class='smright' style='color:#333;font-family: Microsoft Yahei;word-wrap: break-word; text-align: right;margin:5px;'>" . C('WEB_SITE_TITLE') . "</p>
                //             <p class='smright' style='color:#333;font-family: Microsoft Yahei;word-wrap: break-word; text-align: right;margin:5px;  '>" . date('Y年m月d日', time()) . "</p>
                //         </div>
                //         <div class='foot' style='background-color: #f9f9f9; height:80px; margin-top:20px; text-indent: 32px;'>
                //             <p class='info' style='margin:5px; color:#777; font-size:14px;padding-top:20px;'>本邮件由系统自动发送，请勿直接回复！如有任何疑问，请联系我们的客服人员。</p>
                //         </div>
                //     </div>
                // ";

                $mail_data['content'] = "<div style='width:100%;height:100%;background-color:#ecf0f5;padding:100px 0'><div style='border:2px solid #ccc;max-width:450px;padding:30px;border-radius: 15px;margin:0 auto;font-size:14px;font-family:Microsoft Yahei;line-height: 30px; color:#444; text-align: justify;background-color:#fff;'>
                    <p style='padding:0;margin:0;'><span>" . I('post.manager') . "</span>亲爱的用户，您好：</p>
                    <p style='padding:0;margin:0;margin-top:10px;'>您现在正在申请通行证邮箱修改，请点击下面链接完成操作：</p>
                    <p style='padding:0;margin:0;margin-top:10px;'><a style='color:#428BCA' href=" . $url . " target='_blank'>点击修改邮箱</a></p>
                    <p style='padding:0;margin:0;margin-top:10px;'>如果上述文字点击无效，请把下面网页地址复制到浏览器地址栏中打开：</p>
                    <p style='padding:0;margin:0;margin-top:10px;'><a style='color:#428BCA' href=" . $url . " target='_blank'>" . $url . "</a></p>
                    <p style='padding:0;margin:0;margin-top:10px;'>如果您没有进行找回密码操作，请忽略此邮件。</p>
                    <p style='padding:0;margin:0;margin-top: 20px;'>游戏联运平台运营团队</p>
                    <p style='padding:0;margin:0;margin-top: 20px;'>北京勇士互娱科技有限公司</p>
                    <p style='padding:0;margin:0'>" . date('Y-m-d H:i') . "</p>
                </div><p style='font-size: 14px;margin: 0 auto;color: #666;padding-top: 0px;max-width: 500px;margin: 0 auto;text-align: right;'>此邮件为系统邮件，请勿直接回复</p></div>";


                //拼接邮件内容信息结束
                //发送邮件
                $result = D('Addons://Email/Email')->send($mail_data);
                if ($result) {
                    $this->ajaxReturn(returnInfo('1', '邮箱发送成功!', null, $this->infoType), $this->returnType);
                } else {
                    $this->ajaxReturn(returnInfo('-5', '邮箱发送失败，请重新操作!', null, $this->infoType), $this->returnType);
                }
            } else {
                $this->ajaxReturn(returnInfo('-7', '查询的用户不存在!', null, $this->infoType), $this->returnType);
            }
        } else {
            $this->ajaxReturn(returnInfo('-3', '验签失败!', null, $this->infoType), $this->returnType);
        }
        exit;
    }

    /**
     * 新人注册随机5个用户问新人个招呼
     * http://www.isgcn.com/api/Love/greet/
     * @author
     */
    public function greet() {
        if ($this->verifySign()) {
            $post = $this->requestData;
            //$strWhere = ($post['gender']=='-1') ? "t1.id >= t2.id AND t1.female = 1 AND greet_type = 2" : "t1.id >= t2.id AND t1.male = 1 AND greet_type = 2";
            $greet_where = ($post['gender'] == '-1') ? 'female = 1' : 'male = 1';
            $user_object = D('Admin/User');
            $greet_object = D('Love/Greet');
            $green_sub_where = $greet_where . " AND greet_type = 2 ";

            //获取符合条件的打招呼总数量
            $green_count = $greet_object->where($green_sub_where)->count();
            //随机获取不同数量的打招呼数据，至少10条
            $green_limit = rand(10, $green_count);
            $greet_list = $greet_object->alias('t1')->field('t1.id as gid,t1.greetans,t1.greeting')->where($green_sub_where)->limit($green_limit)->select();
            //从获取到的打招呼中随机取5条
            $greet_list_key = array_rand($greet_list, 5);
            foreach ($greet_list_key as $g) {
                $greet[] = $greet_list[$g];
            }

            $userWhere = ($post['gender'] == '-1') ? 1 : "-1";
            $user_sub_where = "gender=" . $userWhere;
            //获取符合条件的用户总数量
            $user_count = $user_object->where($user_sub_where)->count();
            //随机获取不同数量的用户数据，至少10条
            $user_limit = rand(10, $user_count);
            $user_list = $user_object->alias('t1')->field('t1.id as uid,t1.id,t1.nickname,t1.username,t1.gender,t1.avatar,t2.birthday')->join('__LOVE_PROFILE__ t2 ON t1.id=t2.uid', 'LEFT')->where($user_sub_where)->limit($user_limit)->select();
            //从获取到的用户中随机取5条
            $user_list_key = array_rand($user_list, 5);
            foreach ($user_list_key as $u) {
                $userModel[] = $user_list[$u];
            }
            unset($greet_list);
            unset($greet_list_key);
            unset($user_list);
            unset($user_list_key);

            $toWhere['id'] = $post['to_uid'];
            $toUser = $user_object->field('id')->where($toWhere)->find();
            $toUsers[] = $toUser['id'];
            $data = array();
            //$profile = D("Love/Profile");
            if (!empty($userModel)) {
                foreach ($userModel as $key => $val) {
                    $greetans = \Common\Util\Think\Str::parseAttr($greet[$key]['greetans']);
                    $greetans_arr = array_values($greetans);

                    $data[] = array(
                        'greeting' => $greet[$key]['greeting'],
                        'greetans' => $greetans_arr,
                        'username' => $val['username'],
                        'nickname' => $val['nickname'],
                        'gender' => $val['gender'],
                        'uid' => $val['uid'],
                        //'avatar' =>get_cover($val['avatar'],'default'),
                        'avatar' => $val['avatar'],
                        'avatar_url' => $val['avatar_url'],
                        'age' => birthday($val['birthday']),
                    );
                    $from_user_data = [
                        'id' => $val['uid'],
                        'nickname' => $val['nickname'],
                        'avatar_url' => $val['avatar_url'],
                    ];
                    unset($greetans_arr);
                    unset($greetans);
                    $msgRes = $this->huanxin->sendText($val['username'], 'users', $toUsers, $greet[$key]['greeting'], $from_user_data);
                    $log['from_uid'] = $val['username'];
                    $log['to_uid'] = $toUsers;
                    $log['content'] = $greet[$key]['greeting'];
                    $log['from_user_data'] = $from_user_data;
                    $log['result'] = $msgRes;
                    $this->writeToLog('greet', $log);
                    if (!empty($msgRes['error'])) {
                        $this->ajaxReturn(returnInfo('-7', $msgRes['error_description'], null, $this->infoType), $this->returnType);
                    }
                }
            }
            $this->ajaxReturn(returnInfo('1', '获取问题成功!', $data, $this->infoType), $this->returnType);
        } else {
            $this->ajaxReturn(returnInfo('-3', '验签失败!', null, $this->infoType), $this->returnType);
        }
    }

    /**
     * 提交问题接口
     * http://www.isgcn.com/api/game/Public/siteInfo.api
     * @author
     */
    public function submitQuery() {
        if ($this->verifySign()) {
            $post = $this->requestData;

            if (empty($post['to_uid'])) {
                $this->ajaxReturn(returnInfo('-7', '发信 UID 不能为空!', null, $this->infoType), $this->returnType);
            }
            $post['from_data'] = json_decode($post['from_data'], true);
            //dump(is_array($post['from_data']));
            if (!is_array($post['from_data'])) {
                $this->ajaxReturn(returnInfo('-7', '非法提交!', null, $this->infoType), $this->returnType);
            }
            $this->writeToLog('submitQuery-post', $post['from_data']);

            $fromWhere['a.id'] = $post['to_uid'];
            $fromRes = D('Admin/User')->alias('a')->field('a.id,a.username,a.gender,a.nickname')->where($fromWhere)->order('a.id asc')->find();

            $from_user_data = [
                'id' => $fromRes['id'],
                'nickname' => $fromRes['nickname'],
                'avatar_url' => $fromRes['avatar_url'],
            ];

            foreach ($post['from_data'] as $key => $val) {
                $to['a.id'] = $val['from_uid'];
                $toRes = D('Admin/User')->alias('a')->field('a.id,a.username,a.gender,a.nickname')->where($to)->order('a.id asc')->find();
                $msgRes = $this->huanxin->sendText($fromRes['username'], 'users', [$toRes['username']], $val['greetans'], $from_user_data);
                $log['from_username'] = $fromRes['username'];
                $log['to_username'] = $toRes['username'];
                $log['content'] = $val['greetans'];
                $log['from_user_data'] = $from_user_data;
                $log['result'] = $msgRes;
                $this->writeToLog('submitQuery', $log);
                if (!empty($msgRes['error'])) {
                    $this->ajaxReturn(returnInfo('-7', $msgRes['error_description'], null, $this->infoType), $this->returnType);
                }
            }
            $this->ajaxReturn(returnInfo('1', '提交问题成功!', null, $this->infoType), $this->returnType);
        } else {
            $this->ajaxReturn(returnInfo('-3', '验签失败!', null, $this->infoType), $this->returnType);
        }
    }

    /**
     * 推荐用户接口
     * http://www.isgcn.com/api/game/Public/siteInfo.api
     * @author
     */
    public function recommend() {
        if ($this->verifySign()) {
            $post = $this->requestData;
            if (empty($post['gender']))
                $this->ajaxReturn(returnInfo('-7', '必传参数 Gender 不能为空!', null, $this->infoType), $this->returnType);
            $userWhere['a.gender'] = ($post['gender'] == '-1') ? '1' : '-1';
            $userWhere['a.avatar'] = array('GT', 0);
            /*
              SELECT *
              FROM `table` AS t1 JOIN (SELECT ROUND(RAND() * ((SELECT MAX(id) FROM `table`)-(SELECT MIN(id) FROM `table`))+(SELECT MIN(id) FROM `table`)) AS id) AS t2
              WHERE t1.id >= t2.id
              ORDER BY t1.id LIMIT 1;
             */
            $userData = D('Love/User')->alias('a')->field('a.id,a.nickname,a.gender,a.avatar,b.area')->join('__LOVE_PROFILE__ b ON a.id=b.uid', 'LEFT')->where($userWhere)->order('rand() desc')->limit(12)->select();
            $this->ajaxReturn(returnInfo('1', '获取用户成功!', $userData, $this->infoType), $this->returnType);
        }else {
            $this->ajaxReturn(returnInfo('-3', '验签失败!', null, $this->infoType), $this->returnType);
        }
    }

    /**
     *  获取附近好友
     * http://www.isgcn.com/api/Love/Public/nearbyFriends.api
     * */
    public function nearbyFriends() {
        if ($this->verifySign()) {
            $post = $this->requestData;
            if (empty($post['gender']))
                $this->ajaxReturn(returnInfo('-7', '必传参数 Gender 不能为空!', null, $this->infoType), $this->returnType);

            $userWhere['a.gender'] = ($post['gender'] == '-1') ? '1' : '-1';
            //$userWhere['a.avatar'] = array('GT',0);
            $page = $post['page'] > 0 ? $post['page'] : 1;
            $offset = $post['offset'] > 0 ? $post['offset'] : 15;
            $count = D('Love/User')->alias('a')->join('__LOVE_PROFILE__ c ON c.uid=a.id', 'LEFT')->where($userWhere)->count();
            $total = ceil($count / $offset); //总页数  = 总条数/条数
            $page = (abs($page) - 1) * $offset; //从$page开始
            //附近的人缓存1小时
            $s = S(array('type' => 'File', 'expire' => 3600, 'prefix' => 'nearbyFriends_'));
            $skey = is_login() ? is_login() : $post['gender'];
            $skey = $page . $offset . $skey . $post['gender'];
            $userData = S($skey);
            if (empty(S($userData))) {
                if ($page > 1) {
                    $userData = D('Love/User')->alias('a')->field('a.id,a.nickname,a.avatar,c.area,c.birthday,c.monolog,c.astro,c.marrystatus,c.blood,c.education,c.height,c.income,c.housing,c.caring,c.havechildren')->join('__LOVE_PROFILE__ c ON c.uid=a.id', 'LEFT')->where($userWhere)->limit($page, $offset)->select();
                } else {
                    for ($i = 0; $i < 15; $i++) {
                        $userData[] = D('Love/User')->alias('a')->field('a.id,a.nickname,a.avatar,c.area,c.birthday,c.monolog,c.astro,c.marrystatus,c.blood,c.education,c.height,c.income,c.housing,c.caring,c.havechildren')->join('__LOVE_PROFILE__ c ON c.uid=a.id', 'LEFT')->where($userWhere)->order('rand() desc')->find();
                    }
                }
                //join('__LOVE_COND__ b  ON b.uid=a.id')->
                //b.areas,b.startage,b.endage,b.startheight,b.endheight,b.startedu,b.salary,b.salaryup,
                if (!empty($userData)) {
                    foreach ($userData as $key => $val) {
                        $range = rand(10, 200) / 100;
                        $userData[$key]['range'] = $range;
                        $userData[$key]['age'] = birthday($val['birthday']);
                        $userCond = D('Love/Cond')->field('areas,startage,endage,startheight,endheight,startedu,salary,salaryup')->find($userData[$key]['id']);
                        if (!empty($userCond))
                            $userData[$key] = array_merge($userData[$key], $userCond);
                    }
                }
                foreach ($userData as $uniqid => $row) {
                    foreach ($row as $key => $value) {
                        $arrSort[$key][$uniqid] = $value;
                    }
                }
                array_multisort($arrSort['range'], constant('SORT_ASC'), $userData);
                $memRes = S($skey, $userData); //缓存数据
            }
            //dump($userData);exit;
            $this->ajaxReturn(returnInfo('1', '获取附近好友成功!', $userData, $this->infoType), $this->returnType);
        } else {
            $this->ajaxReturn(returnInfo('-3', '验签失败!', null, $this->infoType), $this->returnType);
        }
    }

    /**
     * 获取缘分好友
     * @param gender int 性别
     * @param page int  页码
     */
    public function getKarmaFriends() {
        if ($this->verifySign()) {
            $post = $this->requestData;
            if (empty($post['gender']) && empty($post['page']))
                $this->ajaxReturn(returnInfo('-7', '缺少必传参数!', null, $this->infoType), $this->returnType);

            $where['a.gender'] = ($post['gender'] == '-1') ? '1' : '-1';
            $where['a.avatar'] = array('GT', 0);
            $data = D('Love/User')->getKarmaList($where, $post['page'], $post['offset']);
            $this->ajaxReturn(returnInfo('1', '获取缘分好友成功!', $data['data'], $this->infoType), $this->returnType);
        }else {
            $this->ajaxReturn(returnInfo('-3', '验签失败!', null, $this->infoType), $this->returnType);
        }
    }

    /**
     * 获取系统消息
     * http://www.isgcn.com/api/game/user/message.api
     * @author
     */
    public function message() {
        if ($this->verifySign()) {
            $post = $this->requestData;
            if (empty($post['uid']))
                $this->ajaxReturn(returnInfo('-7', '必传参数 UID 不能为空!', null, $this->infoType), $this->returnType);

            $message = D('Love/Message')->where("to_uid=" . $post['uid'])->find();
            $this->ajaxReturn(returnInfo('1', '获取系统消息成功!', $message, $this->infoType), $this->returnType);
        }else {
            $this->ajaxReturn(returnInfo('-3', '验签失败!', null, $this->infoType), $this->returnType);
        }
    }

    /**
     *  打招呼接口
     * @param from_uid  int  发信id
     * @param to_uid    int  收信id
     * @return count 成功个数
     * */
    public function privateLetter() {
        if ($this->verifySign()) {
            $post = $this->requestData;
            if (empty($post['from_uid']) && empty($post['to_uid']))
                $this->ajaxReturn(returnInfo('-7', '必传参数 FROM_UID 和 TO_UID 不能为空!', null, $this->infoType), $this->returnType);

            $fromWhere['a.id'] = $post['from_uid'];
            $fromRes = D('Admin/User')->alias('a')->field('a.id,a.username,a.gender,a.nickname')->where($fromWhere)->order('a.id asc')->find();
            if (!$fromRes) {
                $this->ajaxReturn(returnInfo('-6', '非法用户', null, $this->infoType), $this->returnType);
            }
            if (is_array($post['to_uid'])) {
                $toWhere['a.id'] = array('in', implode(',', $post['to_uid']));
            } else {
                $toWhere['a.id'] = $post['to_uid'];
            }
            $toRes = D('Admin/User')->alias('a')->field('a.id,a.username,a.gender')->where($toWhere)->order('a.id asc')->select();
            if (!$toRes) {
                $this->ajaxReturn(returnInfo('-5', '非法用户', null, $this->infoType), $this->returnType);
            }
            //$res = D('Admin/User')->alias('a')->field('a.username,b.gender')->join('__LOVE_PERSON__ b ON b.uid=a.id')->where($where)->select();
            $toUsers = array();
            if (!empty($toRes)) {
                foreach ($toRes as $key => $val) {
                    $toUsers[] = $val['username'];
                }
            }
            //$msg = ($fromRes['gender'] == 1) ? 'HI，有一位帅哥给你打招呼，希望能有机会跟你见面约会。' : 'HI，有一位美女给你打招呼，希望能跟你聊天，约会。';
            /* 			$where['ask_type'] =0;
              if($fromRes['gender'] == 1){
              $where['male'] =1;
              }else{
              $where['female'] =1;
              } */
            $greet_where = ($fromRes['gender'] == '1') ? 'female = 1' : 'male = 1';
            //$greet_where['id'] = array('GT',211);
            $greet_model = D('Love/Greet');
            $green_sub_where = $greet_where . " AND ask_type = 0";
            $greetMax = $greet_model->field("MAX(id)")->where($green_sub_where)->buildSql();
            $greetMin = $greet_model->field("MIN(id)")->where($green_sub_where . "  AND id>211")->buildSql();
            $strWhere = $green_sub_where . " AND id>=(($greetMax - $greetMin)*rand() + $greetMin)";

            $msginfo = $greet_model->where($strWhere)->limit(1)->/* fetchSql(true)-> */find();
            $msg = $msginfo['greeting']; //dump($msginfo);dump($fromRes);dump($toUsers);exit;

            $from_user_data = [
                'id' => $fromRes['id'],
                'nickname' => $fromRes['nickname'],
                'avatar_url' => $fromRes['avatar_url'],
            ];
            $msgRes = $this->huanxin->sendText($fromRes['username'], 'users', $toUsers, $msg, $from_user_data);
            $log['from_uid'] = $fromRes['username'];
            $log['to_uid'] = $toUsers;
            $log['content'] = $msg;
            $log['from_user_data'] = $from_user_data;
            $log['result'] = $msgRes;
            $this->writeToLog('privateLetter', $log);
            $count = array_count_values($msgRes['data']);
            $this->ajaxReturn(returnInfo('1', '打招呼成功!', array('msg' => $msg, 'successcount' => $count), $this->infoType), $this->returnType);
        } else {
            $this->ajaxReturn(returnInfo('-3', '验签失败!', null, $this->infoType), $this->returnType);
        }
    }

    public function userinfo() {
        if ($this->verifySign()) {
            $post = $this->requestData;
            if (empty($post['uid']) || empty($post['from_uid']))
                $this->ajaxReturn(returnInfo('1', 'uid和from_uid都不能为空', null, $this->infoType), $this->returnType);
            $vipInfo = D('Love/Vip')->isVip($post['from_uid'], true);
            $where['uid'] = $post['uid'];
            $where['sid'] = $post['sid'];
            if ($vipInfo) {//VIP会员看到什么
                $msg = 'vip信息获取成功！';
                $data = D('Love/Photo')->where($where)->order('id desc')->select();
                $userfield = 'id,id as uid,nickname,avatar';
                $profilefield = 'birthday,monolog,astro,marrystatus,blood,education,height,income,housing,caring,havechildren';
            } else {//非vip会员看到什么
                $msg = '普通信息获取成功！';
                $data = D('Love/Photo')->where($where)->limit(1)->order('id desc')->select();
                $userfield = 'id,id as uid,nickname,avatar';
                //$profilefield = 'area,birthday';
                $profilefield = 'monolog,astro,marrystatus,blood,income,housing,caring,havechildren';
            }
            $userData = D('Love/User')->field($userfield)->find($post['uid']);
            $userData['photo'] = !empty($data) ? $data : null; //补充相册信息
            $userprofile = D('Love/Profile')->field($profilefield)->find($post['uid']);

            if (!empty($userprofile)) {
                $userprofile['age'] = birthday($userprofile['birthday']);
                $userData = array_merge($userData, $userprofile);
            }
            $this->ajaxReturn(returnInfo('1', $msg, $userData, $this->infoType), $this->returnType);
        } else {
            $this->ajaxReturn(returnInfo('-3', '验签失败!', null, $this->infoType), $this->returnType);
        }
    }

    public function me() {
        if ($this->verifySign()) {
            $post = $this->requestData;
            if (empty($post['uid']))
                $this->ajaxReturn(returnInfo('-5', '必传参数 UID 不能为空!', null, $this->infoType), $this->returnType);
            /*
              内心告白----输入框（需要限定字数）
              昵称    输入
              生日    选择日期
              年龄    自动计算
              星座    选择
              婚姻状况选择
              血型    选择
              学历    选择
              身高    选择
              收入    选择
              住房    选择
              购车    选择
              要小孩  选择
             */
            $where['id'] = $post['uid'];
            //$userInfo = D('Love/User')->alias('a')->field('a.avatar,a.nickname,b.birthday,b.monolog,b.astro,b.marrystatus,b.blood,b.education,b.height,b.income,b.housing,b.caring,b.havechildren')->join('__LOVE_PROFILE__ b on a.id=b.uid')->where($where)->find();
            $userInfo = D('Love/User')->field('avatar,nickname')->where($where)->find();
            $userProfile = D('Love/Profile')->field('area,birthday,nationality,monolog,astro,marrystatus,blood,education,jobs,salary,charmparts,marrystatus,height,weight,income,housing,caring,talive,havechildren')->find($post['uid']);
            if (!empty($userProfile)) {
                $userInfo = array_merge($userInfo, $userProfile); //dump($data);
            }
            if (!empty($userInfo)) {
                $data['profile']['age'] = birthday($userInfo['birthday']) && birthday($userInfo['birthday']) != '未知' ? birthday($userInfo['birthday']) : 18;
                $data['profile']['monolog'] = $userInfo['monolog'];
                $data['profile']['nickname'] = $userInfo['nickname'];
                $data['profile']['birthday'] = $userInfo['birthday'];
                $data['profile']['avatar'] = $userInfo['avatar'];
                $data['profile']['avatar_url'] = $userInfo['avatar_url'];
                $data['profile']['astro'] = $userInfo['astro'];
                $data['profile']['marrystatus'] = $userInfo['marrystatus'];
                $data['profile']['blood'] = $userInfo['blood'];
                $data['profile']['education'] = $userInfo['education'];
                $data['profile']['height'] = $userInfo['height'];
                $data['profile']['income'] = $userInfo['income'];
                $data['profile']['housing'] = $userInfo['housing'];
                $data['profile']['caring'] = $userInfo['caring'];
                $data['profile']['havechildren'] = $userInfo['havechildren'];

                $data['profile']['nationality'] = $userInfo['nationality'];
                $data['profile']['weight'] = $userInfo['weight'];
                $data['profile']['jobs'] = $userInfo['jobs'];
                $data['profile']['salary'] = $userInfo['salary'];
                $data['profile']['charmparts'] = $userInfo['charmparts'];
                $data['profile']['talive'] = $userInfo['talive'];
                $data['profile']['area'] = $userInfo['area'];
                $sum = count($data['profile']);
                $finish = count(array_filter($data['profile']));
                $data['ratio'] = ceil(($finish / $sum) * 100) . '%';
            }//dump($data);
            $this->ajaxReturn(returnInfo('1', '获取我的资料成功!', $data, $this->infoType), $this->returnType);
        } else {
            $this->ajaxReturn(returnInfo('-3', '验签失败!', null, $this->infoType), $this->returnType);
        }
    }

    /**
     *
     * 交友条件(测试)
     */
    public function mfcond() {

        if ($this->verifySign()) {
            $post = $this->requestData;
            if (empty($post['uid'])) {
                $this->ajaxReturn(returnInfo('-5', '必传参数 UID 不能为空!', null, $this->infoType), $this->returnType);
            }
            $user_model = D('Admin/User');
            $user_cond['id'] = $post['uid'];
            $user_res = $user_model->where($user_cond)->find();
            if (!$user_res) {
                $this->ajaxReturn(returnInfo('-4', '非法用户操作!', null, $this->infoType), $this->returnType);
            }
            $cond_model = D('Love/Cond');
            $cond['areas'] = $post['areas']; //地区
            $cond['startage'] = $post['startage']; //最小年龄
            $cond['endage'] = $post['endage']; //最大年龄
            $cond['startheight'] = $post['startheight']; //最小身高
            $cond['endheight'] = $post['endheight']; //最大身高
            $cond['startedu'] = $post['startedu']; //学历
            $cond['salary'] = $post['salary']; //收入
            $cond['salaryup'] = $post['salaryup']; //1 xx收入以上 2 xx收入以下
            $cond['uid'] = $post['uid']; //当前用户
            $where_cond['uid'] = $post['uid'];

            $res = $cond_model->where($where_cond)->find();
            if ($res) {
                $cond_model->where($where_cond)->save($cond);
                $this->ajaxReturn(returnInfo('1', '编辑成功!', null, $this->infoType), $this->returnType);
            } else {
                $status = $cond_model->where($where_cond)->add($cond);
                if ($status) {
                    $this->ajaxReturn(returnInfo('1', '编辑成功!', null, $this->infoType), $this->returnType);
                } else {
                    $this->ajaxReturn(returnInfo('-5', '操作失败', null, $this->infoType), $this->returnType);
                }
            }
        } else {

            $this->ajaxReturn(returnInfo('-3', '验签失败!', null, $this->infoType), $this->returnType);
        }
    }

    /**
     *
     * 交友条件(获取)
     */
    public function cond() {

        if ($this->verifySign()) {
            $post = $this->requestData;
            if (empty($post['uid'])) {
                $this->ajaxReturn(returnInfo('-5', '必传参数 UID 不能为空!', null, $this->infoType), $this->returnType);
            }
            $cond_model = D('Love/Cond');
            $where_cond['uid'] = $post['uid'];

            $res = $cond_model->where($where_cond)->find();
            if ($res) {
                $this->ajaxReturn(returnInfo('1', '获取成功!', $res, $this->infoType), $this->returnType);
            } else {
                $this->ajaxReturn(returnInfo('-5', '获取失败', null, $this->infoType), $this->returnType);
            }
        } else {

            $this->ajaxReturn(returnInfo('-3', '验签失败!', null, $this->infoType), $this->returnType);
        }
    }

    public function editInfo() {
        if ($this->verifySign()) {
            $post = $this->requestData;
            /*
              "uid"=> "9",//不可空  25
              "monolog" => "爱爱爱爱爱死你了",//内心独白
              "personality" => 4,//个性特征
              "nickname" => "白小白",//昵称
              "interest" => 1,//兴趣爱好
              "astro" => "水瓶",//星座
              "provinceid" => 1,//省ID     +  籍贯的组合信息
              "cityid" => 2,//市ID         +
              "distid" => 3,//区域ID       +
              "communityid" => 4,//社区ID  +
              "nationprovinceid" => 1, //国籍
              "nationcityid" => 2,//国家ID
              "nationality" => 3,//国家城市ID
              "height" => 185,//身高
              "weight" => 75,//体重，单位KG
              "blood" => 2,//血型
              "education" => 5,//学历
              "jobs" => 2,//专业
              "salary" => 1,//月收入
              "charmparts" => 1,//魅力部位
              "marrystatus" => 1,//婚姻状况 1未婚 2已婚 3离异 4丧偶
              "housing" => 1,//住房情况
              "talive" => 1,//和父母同住
              "havechildren" => 1,//是否要小孩
              "area" => "北京",//Ta所在地
              "setarea" => "北京",//Ta所在地   c
              "startage" => 1,//Ta最小年林
              "endage" => 1,//Ta最大年龄  cond表中的优先地区
              "startheight" => 1,//Ta最小体重  cond表中的优先地区
              "endheight" => 1,//Ta最大体重  cond表中的优先地区
              "startedu" => 1,//最低学历  cond表中的优先地区
             * */
            if (empty($post['uid']))
                $this->ajaxReturn(returnInfo('-5', '必传参数 UID 不能为空!', null, $this->infoType), $this->returnType);

            $userModel = D('Admin/User');
            $userModel->startTrans();
            try {
                $userData['nickname'] = $post['nickname'];
                $where['id'] = $post['uid'];
                $isUser = $userModel->where($where)->find();
                if ($isUser) {
                    $userRes = $userModel->where($where)->save($userData);
                } else {
                    $this->ajaxReturn(returnInfo('-5', '用户不存在！', null, $this->infoType), $this->returnType);
                    $userModel->rollback();
                }
                unset($where);
                $where['uid'] = $post['uid'];
                $profileModel = D('Love/Profile');
                $profile['uid'] = $post['uid'];
                $profile['monolog'] = $post['monolog'];
                $profile['personality'] = $post['personality'];
                $profile['interest'] = $post['interest'];
                $profile['astro'] = $post['astro'];
                $profile['provinceid'] = $post['provinceid'];
                $profile['cityid'] = $post['cityid'];
                $profile['distid'] = $post['distid'];
                $profile['area'] = isset($post['area']) ? $post['area'] : '保密';
                $profile['birthday'] = $post['birthday'];
                $profile['communityid'] = $post['communityid'];
                $profile['nationprovinceid'] = $post['nationprovinceid'];
                $profile['nationcityid'] = $post['nationcityid'];
                $profile['nationality'] = $post['nationality'];
                $profile['height'] = $post['height'];
                $profile['weight'] = $post['weight'];
                $profile['blood'] = $post['blood'];
                $profile['education'] = $post['education'];
                $profile['income'] = $post['income'];
                $profile['jobs'] = $post['jobs'];
                $profile['salary'] = $post['salary'];
                $profile['charmparts'] = $post['charmparts'];
                $profile['marrystatus'] = $post['marrystatus'];
                $profile['housing'] = $post['housing'];
                $profile['talive'] = $post['talive'];
                $profile['havechildren'] = $post['havechildren'];
                $isProfile = $profileModel->where($where)->find();
                if ($isProfile) {
                    $profileRes = $profileModel->where($where)->save($profile);
                } else {
                    $profileRes = $profileModel->add($profile);
                }
                $condModel = D('Love/Cond');
                //$cond['setarea'] = $post['setarea'];
                //$cond['areas'] = $post['setarea'];
                $cond['startage'] = $post['startage'];
                $cond['endage'] = $post['endage'];
                $cond['startheight'] = $post['startheight'];
                $cond['endheight'] = $post['endheight'];
                $cond['startedu'] = $post['startedu'];
                $where_cond = ['uid' => $post['uid']];
                $condRes = $condModel->where($where_cond)->find(); //dump($condRes);exit;
                if ($condRes) {
                    $status = $condModel->where($where_cond)->save($cond);
                } else {
                    $cond['uid'] = $post['uid'];
                    $status = $condModel->add($cond);
                }
                $userModel->commit();
                $this->ajaxReturn(returnInfo('1', '编辑成功!', null, $this->infoType), $this->returnType);
            } catch (Exception $e) {
                $error = current($e);
                $this->ajaxReturn(returnInfo('-8', $error, null, $this->infoType), $this->returnType);
            }
        } else {
            $this->ajaxReturn(returnInfo('-3', '验签失败!', null, $this->infoType), $this->returnType);
        }
    }

    public function listenMessage() {
        if ($this->verifySign()) {
            $post = $this->requestData;
            if (empty($post['message_id']))
                $this->ajaxReturn(returnInfo('-7', '必传参数 MESSAGE_ID 不能为空!', null, $this->infoType), $this->returnType);
            if (empty($post['from_uid']))
                $this->ajaxReturn(returnInfo('-7', '必传参数 FROM_UID 不能为空!', null, $this->infoType), $this->returnType);
            if (empty($post['to_uid']))
                $this->ajaxReturn(returnInfo('-7', '必传参数 TO_UID 不能为空!', null, $this->infoType), $this->returnType);
            if (empty($post['message']))
                $this->ajaxReturn(returnInfo('-7', '必传参数 MESSAGE 不能为空!', null, $this->infoType), $this->returnType);
            $msg = json_decode($post['message'], true);
            $post['message'] = $msg['type'] == 'txt' ? $msg['msg'] : $msg['type'];

            $talk = D('Love/Talk');
            $res = $talk->create();
            if ($res) {
                if ($post['is_read']) {
                    $ret = $talk->where(array('message_id' => $post['message_id']))->save(array('is_read' => $post['is_read'], 'update_time' => time()));
                } else {
                    $post['create_time'] = time();
                    $ret = $talk->add($post);
                }
                if ($ret === false) {
                    $this->ajaxReturn(returnInfo('-5', $talk->getError(), null, $this->infoType), $this->returnType);
                } else {
                    $this->ajaxReturn(returnInfo('1', '监听聊天信息成功!', null, $this->infoType), $this->returnType);
                }
            } else {
                $this->ajaxReturn(returnInfo('-5', $talk->getError(), null, $this->infoType), $this->returnType);
            }
        } else {
            $this->ajaxReturn(returnInfo('-3', '验签失败!', null, $this->infoType), $this->returnType);
        }
    }

    /**
     * 写入日志
     * @param $title
     * @param $messgae array|string
     */
    public function writeToLog($_logFile, $messgae) {
        $now_time = date('Y-m-d H:i:s', time());
        $log_template = "--------------------------------" . $now_time . "--" . $title . "-start---------------------------------------\r\n";
        if (is_array($messgae)) {
            $log_template .= print_r($messgae, true) . "\r\n";
        } else {
            $log_template .= $messgae;
        }
        $log_template .= "---------------------------------end---------------------------------------";
        file_put_contents('./' . $_logFile . '.log', $log_template, FILE_APPEND);
    }
    
    
    /**
     * 过滤用户敏感信息和客户端无用信息，将用户必要数据包装后返回。
     * @param  array 	$user 		用户信息
     * @param  bool 	$genToken 	是否生成鉴权码
     * @return array       			包装后的用户信息
     */
    private function wrap_user_info($user, $genToken) {

        if ($genToken) {
            $expiry = time() + C('API_TOKEN_INTERVAL');
            $token = $this->generate_access_token($user, $expiry);
            $user['access_token'] = $token;
            $user['token_expiry'] = $expiry;
        }
        return $user;
    }

}
