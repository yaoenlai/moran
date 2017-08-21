<?php
namespace Yyadmin\Model;
use Think\Model;

class YyadminUserModel extends Model
{
    protected $_auto = array (
        array('status','1'),  
        array('grade','3'),
        array('login', '0'),
        array('sid','getSid',2,'callback'),
        );
    protected $_validate = array(
        array('username','','公司名称已经存在！',0,'unique',1),
        );
    protected function getSid(){
        return implode(',', I("post.slist"));
    } 
    /**
     * 用户登录认证
     * @param  string  $username 用户名
     * @param  string  $password 用户密码
     * @param  integer $type     用户名类型 （1-用户名，2-邮箱，3-手机，4-UID）
     * @return integer           登录成功-用户ID，登录失败-错误编号
     */
    public function login($username, $password, $type='1')
    {
        $pass = strtolower(md5($password));
        switch ($type)
        {
            case '1':
                $data = array(
                    'username'  => $username,
                    'password'  => $pass,
                );
                break;
            case '2':
                $data = array(
                    'email'  => $username,
                    'password'  => $pass,
                );
                break;
            case '3':
                $data = array(
                    'phone'  => $username,
                    'password'  => $pass,
                );
                break;
            default:return false;
        }
        $data['status'] = '1';
        $user = $this->field(true)->where($data)->find();
        $uid = $user['uid'];
        if(empty($user) || 1 != $user['status']) {
            
            $this->error = '用户不存在或已被禁用！'; //应用级别禁用
            return false;
        } else {

            //记录行为
            //action_log('user_login', 'member', $uid, $uid);
    
            /* 登录用户 */
            $this->autoLogin($user);
            return true;
        }
    }
    
    /**
     * 自动登录用户
     * @param  integer $user 用户信息数组
     */
    private function autoLogin($user){
        /* 更新登录信息 */
        $data = array(
            'uid'             => $user['uid'],
            'login'           => array('exp', '`login`+1'),
            'last_login_time' => NOW_TIME,
            'last_login_ip'   => get_client_ip(1),
        );
        $this->save($data);
    
        /* 记录登录SESSION和COOKIES */
        session('yuid', $user['uid']);
        session('admin_user', $user);
    }
}