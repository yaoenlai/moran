<?php
/*
 * 定时脚本,针对新用户打招呼脚本
 * @author yyl
 * @email 944677073@qq.com
 * */
namespace Love\Shell;
class UserShell extends Shell {

    public function _initialize()
    {
        parent::_initialize();

    }

    /**
     *  修复用户表 字段 birthday
     *  运行方式: /usr/local/php/bin/php shell.php Love/User/fixedUserbirthday
     */
    public function fixedUserbirthday() {

        $user_object = D('Love/User');
        $count = $user_object->count();
        //每100条一次
        $page_no = 100;
        $page = ceil($count/$page_no);
        for($i = 0; $i < $page; $i++) {
            $list_users = $user_object->order('id')->limit($i*$page_no . ','.$page_no)->select();
            if($list_users) {
                foreach($list_users as $v) {
                    $birthday = $this->randomDate('1992-01-01', '1997-12-31', true);
                    $user_object->where('id='.$v['id'])->save(['birthday' => $birthday]);
                }
            }
            unset($list_users);
        }
    }

    /**
     *  修复用户表 字段 birthday && provinceid && cityid
     *  运行方式: /usr/local/php/bin/php shell.php Love/User/fixedUserprofile
     */
    public function fixedUserprofile() {

        $profile_model = D('Love/Profile');
        $count = $profile_model->count();
        //每100条一次
        $page_no = 100;
        $page = ceil($count/$page_no);
        for($i = 0; $i < $page; $i++) {
            $list_users = $profile_model->order('uid')->limit($i*$page_no . ','.$page_no)->select();
            if($list_users) {
                foreach($list_users as $v) {
                    $birthday = $this->randomDate('1992-01-01', '1997-12-31', true);
                    S('provinceid',null);
                    $area = $this->getRandArea();
                    $data = [
                        'birthday' => $birthday,
                        'provinceid' => $area['provinceid'],
                        'cityid' => $area['cityid']
                    ];
                    $profile_model->where('uid='.$v['uid'])->save($data);
                }
            }
            unset($list_users);
        }
    }

    /**
     * @return array
     */
    public function getRandArea() {
        $area_model = D('Admin/Area');
        $cache_provinceid = S('provinceid');
        $provinceid = 0;
        if(!$cache_provinceid) {
            $cache_provinceid = $area_model->field('id')->where(['rootid' => 0])->select();
            S('provinceid',$cache_provinceid,3600);
        }
        // 尼玛 array_rand 第二个参数来确定要选出几个元素。如果选出的元素不止一个，则返回包含随机键名的数组，否则返回该元素的键名。
        $province_rand_key = array_rand($cache_provinceid,1);
        $provinceid = $cache_provinceid[$province_rand_key]['id'];
        $city_cache_key = $provinceid.'_cityid';
        $cache_cityid = S($city_cache_key);
        if(!$cache_cityid) {
            $cache_cityid = $area_model->field('id')->where(['rootid' => $provinceid])->select();
            S($city_cache_key,$cache_cityid,3600);
        }
        $city_key = array_rand($cache_cityid,1);
        return [
            'provinceid' => $provinceid,
            'cityid' => $cache_cityid[$city_key]['id'],
        ];

    }

    /**
     * 随机一个时间
     * @param $begintime
     * @param string $endtime
     * @param bool $format 时间戳/ 时间
     * @return int
     */
    public function randomDate($begintime, $endtime, $format = false, $format_type = "Y-m-d") {
        $timestamp = mt_rand(strtotime($begintime), strtotime($endtime));
        return $format ? date($format_type, $timestamp) : $timestamp;
    }

    /**
     * 扫描未注册环信的用户 进行环信注册
     * 运行方式: /usr/local/php/bin/php shell.php Love/User/scannerRegister
     */
    public function scannerRegister() {
        $user_object = D('Love/User');
        $hxUser_object = D('Love/HxUser');
        $map['hxuuid'] = array('exp', 'IS NULL');
        $count = $user_object->where($map)->count();
        //每100条一次
        $total_times = ceil($count/100);
        for($i = 0; $i < $total_times; $i++) {
            $list_users = $user_object->where($map)->order('id')->limit($i*100 . ',100')->select();
            if($list_users) {
                foreach($list_users as $v) {
                    $hxUser = $this->huanxin->createUser($v['username'],$v['password'],$v['nickname']);
                    $hxData['uid'] = $v['id'];
                    $hxData['uuid'] = $hxUser['entities'][0]['uuid'];
                    $hxData['type'] = $hxUser['entities'][0]['type'];
                    $hxData['username'] = $hxUser['entities'][0]['username'];
                    $hxData['nickname'] = $v['nickname'];
                    $hxData['status'] = 1;
                    $hxData['password'] = $v['password'];
                    $hxUser_object->create($hxData);
                    $status = $hxUser_object->add();
                    if($status) {
                        $user_object->where('id='.$v['id'])->save(['hxuuid' => $hxData['uuid']]);
                    }
                }
            }
            unset($list_users);
        }
    }
    /**
     * 批量注册环信
     * 运行方式: /usr/local/php/bin/php shell.php Love/User/batchRegister/gender/1/number/10
     */
    public function batchRegister() {
        if(!isset($_GET['gender']) or !in_array($_GET['gender'], [-1,1])) {
            echo '参数错误,正确用法：/usr/local/php/bin/php shell.php Love/User/batchRegister/gender/1/number/10';
            exit;
        }
        if(!isset($_GET['number']) or !is_numeric($_GET['number'])) {
            echo '参数错误,正确用法：/usr/local/php/bin/php shell.php Love/User/batchRegister/gender/1/number/10';
            exit;
        }
        $success = $fail = 0;
        $number = $_GET['number'];
        $gender = $_GET['gender'];
        for($i = 0; $i < $number; $i++) {
            if($this->_register($gender)) {
                $success++;
            } else {
                $fail++;
            }
            sleep(1);
        }
        echo '成功注册'.$success.'个会员，失败'.$fail.'个会员注册';
    }

    public function _register($gender) {
        $nickName = D('Admin/SysNicks')->randomNickname($gender);//随机获取一个昵称
        $user_object = D('Love/User');
        $user_object->startTrans();
        $post['gender'] = $gender;//用户名
        $post['nickname'] = $nickName;//用户名
        $post['username'] = 'yh'.substr(time(),2,10);//用户名
        $post['password'] =  'mm'.rand(pow(10,(8-1)), pow(10,8)-1);//生成八位随机密码
        $post['user_type'] =  1;
        $post['reg_type'] =  'system';
        //注册 环信账号
        $user_object_model = $user_object->create($post);

        if(!$user_object_model){
            $user_object->rollback();
        }
        $userId = $user_object->add();//成功返回自增id 例如：21
        $hxUser = $this->huanxin->createUser($post['username'],$post['password'],$post['nickname']);

        $hxUser_object = D('Love/HxUser');
        $hxData['uid'] = $userId;
        $hxData['uuid'] = $hxUser['entities'][0]['uuid'];
        $hxData['type'] = $hxUser['entities'][0]['type'];
        $hxData['username'] = $hxUser['entities'][0]['username'];
        $hxData['nickname'] = $nickName;
        $hxData['status'] = 1;
        $hxData['password'] = $post['password'];
        $hxUser_object_model = $hxUser_object->create($hxData);
        $hxUser_object->add();
        if(!$hxUser_object_model){
            $user_object->rollback();
        }

        $love_attr = D('Love/Attr');
        $attr['uid'] = $userId;
        $attr['privacy'] = 1;
        $love_attr_model = $love_attr->create($attr);
        if(!$love_attr_model){
            $user_object->rollback();
        }
        $love_attr->add();
        $love_profile = D('Love/Profile');
        $attr['uid'] = $userId;
        $love_profile_model = $love_profile->create($attr);

        if(!$love_profile_model){
            $user_object->rollback();
        }
        $love_profile->add();
        $status = $user_object->commit();
        return $status;
    }

}