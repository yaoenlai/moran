<?php

/**
 * @Created by kevin(askyiwang@gmail.com).
 * @User: kevin
 * @Date: 2017/4/4
 * @Time: 9:46
 * @消息中心模块
 */
namespace Common\Common;

class MessageModule
{
    private $message_table = 'message_center';

    private $system_male_user_table = 'system_male_user';

    private $system_female_user_table = 'system_female_user';

    private $system_o_user_table = 'system_o_user';

    private $system_greet_for_male_table = 'system_greet_for_male';

    private $system_greet_for_female_table = 'system_greet_for_female';

    private $system_greet_for_o_table = 'system_greet_for_o';

    private $message_model = null;

    private $db_driver = null;

    // 消息最大处理量
    private $deal_with_len = 100;

    private $message_middle_server = null;

    private $max_chat_count = 3;

    private $_logFile = './chat.log';

    /**
     * @param \Common\Util\Easemob $message_middle_server
     */
    public function __construct(\Common\Util\Easemob $message_middle_server)
    {
        $this->message_model = D('Love/Greet');
        $this->db_driver = Redis::getInstance();
        $this->message_middle_server = $message_middle_server;
    }

    /**
     * join Message Queue
     * @param array $data
     * @return mixed
     */
    public function joinMessageQueue(array $data) {
        $data['created_at'] = time();
        $data = serialize($data);
        return $this->db_driver->handler->rpush($this->message_table, $data);
    }

    /**
     * 添加当日发送次数
     * @param $user_id
     */
    public function addPushTime($user_id) {
        $now_time = strtotime(date('H:i:s',time()));
        $end_time = strtotime('23:59:59');
        $left_time = $end_time - $now_time;
        $tmp_key = $user_id.'_times';
        if($this->db_driver->handler->get($tmp_key)) {
            $this->db_driver->handler->incr($tmp_key);
        } else {
            $this->db_driver->handler->setex($tmp_key, $left_time, 1);
        }
    }

    /**
     * 检查当日 系统最大发送次数
     * @param $user_id
     * @return bool
     */
    public function checkTimes($user_id) {
        $tmp_key = $user_id.'_times';
        if($v = $this->db_driver->handler->get($tmp_key)) {
            return $v > $this->max_chat_count ? true : false;
        } else {
            return false;
        }
    }
    /**
     * 获取缓存的剩余时间
     * @param $user_id
     */
    public function getLeftTime($user_id) {
        $this->db_driver->handler->TTL($user_id.'_times');
    }

    /**
     * 随机获取系统消息
     * @param int $gender
     * @return mixed
     */
    public function getGreetByCondition($gender = -1, $ask_type = 0) {

        switch($gender) {
            case '1':
            case 'male':
                $table = $this->system_greet_for_male_table.'_'.$ask_type;
                $condition['male'] = 1;
                break;
            case '-1':
            case 'female':
                $table = $this->system_greet_for_female_table.'_'.$ask_type;
                $condition['female'] = 1;
                break;
            default:
                $table = $this->system_greet_for_o_table.'_'.$ask_type;
                $condition['male'] = 1;
                $condition['female'] = 1;
                break;
        }
        if($this->db_driver->handler->lLen($table) > 0) {
            $greet_data = unserialize($this->db_driver->handler->lPop($table));
        } else {
            $condition['status'] = 1;
            $condition['ask_type'] = $ask_type;
			$condition['id'] = array('GT',211);
            // more and more condition
            $field = 'id,male,female,greet_type,ask_type,greeting,greetans,media_type,picture,voice,video';
            $system_greet = $this->message_model->where($condition)->field($field)->select();
            $system_greet = $this->shuffle_assoc($system_greet);
            //随机取出一个系统用户
            $rand_key = rand(0,count($system_greet));
            $greet_data = $system_greet[$rand_key];
            unset($system_greet[$rand_key]);
            if(!empty($system_greet)) {
                foreach($system_greet as $k => $v) {
                    $this->db_driver->handler->rpush($table, serialize($v));
                }
            }
        }
        return $greet_data;
    }

    /**
     * 随机获取系统消息
     * @param int $gender
     * @return mixed
     */
    public function getSystemGreet($gender = -1) {

        switch($gender) {
            case '1':
            case 'male':
                $table = $this->system_greet_for_male_table;
                $condition['male'] = 1;
                break;
            case '-1':
            case 'female':
                $table = $this->system_greet_for_female_table;
                $condition['female'] = 1;
                break;
            default:
                $table = $this->system_greet_for_o_table;
                $condition['male'] = 1;
                $condition['female'] = 1;
                break;
        }
        if($this->db_driver->handler->lLen($table) > 0) {
            $greet_data = unserialize($this->db_driver->handler->lPop($table));
        } else {
            $condition['status'] = 1;
            $condition['ask_type'] = 0;
            $condition['greet_type'] = 2;
            // more and more condition
            $field = 'id,male,female,greet_type,ask_type,greeting,greetans,media_type,picture,voice,video';
            $system_greet = $this->message_model->where($condition)->field($field)->select();
            $system_greet = $this->shuffle_assoc($system_greet);
            //随机取出一个系统用户
            $rand_key = rand(0,count($system_greet));
            $greet_data = $system_greet[$rand_key];
            unset($system_greet[$rand_key]);
            if(!empty($system_greet)) {
                foreach($system_greet as $k => $v) {
                    $this->db_driver->handler->rpush($table, serialize($v));
                }
            }
        }
        return $greet_data;
    }
    /**
     * 乱序获取,后台应该标志系统用户，后台带处理
     * 获取系统用户 lsit table
     * @param int $gender 1 男生 -1 女生
     * @return mixed
     */
    public function getSystemUser($gender = 'female') {

        switch($gender) {
            case '1':
            case 'male':
                $table = $this->system_male_user_table;
                break;
            case '-1':
            case 'female':
                $table = $this->system_female_user_table;
                break;
            default:
                $table = $this->system_o_user_table;
                break;
        }
        //if($this->db_driver->handler->lLen($table) > 0) {
            //$user_data = unserialize($this->db_driver->handler->lPop($table));
        //} else {
            // maybe has system user tag
            $condition = ['gender' => $gender,'avatar'=>['gt',0]];
            $system_users = D('Love/User')->alias('a')->join('__ADDON_HXUSER__ b ON a.id = b.uid')->field('a.id,a.username,a.nickname,a.gender,a.avatar')->where($condition)->select();
			foreach ($system_users as $key => $value) {
				$system_users[$key]['avatar_url'] = str_replace('http://__ROOT__',C('WEB_DEFAULT_DOMAIN'),$system_users[$key]['avatar_url']);
				file_put_contents('/tmp/avatar_url.txt', $system_users[$key]['avatar_url']."\r\n",FILE_APPEND);
			}
            $system_users = $this->shuffle_assoc($system_users);
            //随机取出一个系统用户
            $rand_key = rand(0,count($system_users));
            $user_data = $system_users[$rand_key];
            unset($system_users[$rand_key]);
            if(!empty($system_users)) {
                foreach($system_users as $k => $v) {
                    $this->db_driver->handler->rpush($table, serialize($v));
                }
            }
        //}
        return $user_data;
    }

    /**
     * @param $list
     * @return array
     */
    function shuffle_assoc($list) {
        if (!is_array($list)) return $list;
        $keys = array_keys($list);
        shuffle($keys);
        $random = [];
        foreach ($keys as $key) {
            $random[$key] = $list[$key];
        }
        return $random;
    }

    /**
     * 巨大优化
     * 处理登陆消息
     */
    public function processingMessage() {

        $message_count = $this->db_driver->handler->lLen($this->message_table);

        $has_been = 0;
        $deal_with_len = $this->deal_with_len;
        $tousers = [];
        while(($deal_with_len > 0) && ($message_count > $has_been))
        {
            $has_been++;
            $deal_with_len--;
            $user_info = unserialize($this->db_driver->handler->lPop($this->message_table));
            if($this->checkTimes($user_info['uid'])) {
                continue;
            }
            //gender 1,key 修正为 字符串
            switch($user_info['gender']) {
                case '1':
                    $tousers['male']['users'][] = $user_info['username'];
                    //$tousers['male']['uid'][] = $user_info['uid'];
                    $tousers['male']['message'] = $this->getSystemGreet('-1');
                    break;
                case '-1':
                    $tousers['female']['users'][] = $user_info['username'];
                    //$tousers['female']['uid'][] = $user_info['uid'];
                    $tousers['female']['message'] = $this->getSystemGreet('1');
                    break;
                default:
                    $tousers['o']['users'][] = $user_info['username'];
                    //$tousers['o']['uid'][] = $user_info['uid'];
                    $tousers['o']['message'] = $this->getSystemGreet($user_info['gender']);
                    break;
            }
            $this->addPushTime($user_info['uid']);
        }
        // 给男生发送消息
        if(!empty($tousers['male']['users'])) {
            $system_username = $this->getSystemUser('-1');
            //$this->sendMessageToHx($system_username, $tousers['male']['users'], $tousers['male']['message']);
        }
        // 给女生发送消息
        if(!empty($tousers['female']['users'])) {
            $system_username = $this->getSystemUser('1');
            //$this->sendMessageToHx($system_username, $tousers['female']['users'], $tousers['female']['message']);
        }
        // 给人妖发送消息
        if(!empty($tousers['o']['users'])) {
            $system_username = $this->getSystemUser('male');
            //$this->sendMessageToHx($system_username, $tousers['o']['users'], $tousers['o']['message']);
        }
		//分配机器人给该用户
        $greetrobot_object = D('Love/Greetrobot');
        $robot['uid'] = $user_info['uid'] ? $user_info['uid']:$user_info['id'];
		$robot['robot_uid'] = $system_username['id'] ? $system_username['id'] :$system_username['uid'];
		$robot['times'] = 0;
		$robot_count = $greetrobot_object->where($robot)->count();
		if($robot_count<15){
			$rootres = $greetrobot_object->create($robot,1);//dump($rootres);
	        if($rootres){
	        	$greetrobot_object->add();//分配机器人完成
	        }
		}
		/*
		//写入用户扩展信息
		$userext_object = D('Love/Userext');
		$userext['uid'] = $robot['uid'];
		$userext['robot_num'] = $robot_count ? $robot_count :1;
		$userextres = $userext_object->create($userext,1);//dump($rootres);
		if($userextres){
        	$userext_object->add();//分配机器人完成
        }
		*/
        unset($tousers);
    }

    /**
     * 消息第一追 分发给环信
     * @return bool
     */
    public function sendMessageToHx($system_info, array $tousers, array $message_info){

        $content['greet_type'] = $message_info['greet_type'];
        $content['greeting'] = $message_info['greeting'];
        // 2:问题选择
        $greetans = \Common\Util\Think\Str::parseAttr($message_info['greetans']);

        $system_username = $system_info['username'];

        $ext_data = [
            'id' => $system_info['id'],
            'nickname' => $system_info['nickname'],
            'avatar_url' => $system_info['avatar_url'],
            'greetans' => $greetans
        ];

        $res = $this->message_middle_server->sendText($system_username,'users',$tousers, $message_info['greeting'],$ext_data);
        $log['system_username'] = $system_info;
        $log['tousers'] = $tousers;
        $log['content'] = $content;
        $log['result'] = $res;
        $this->writeToLog('send-message',$log);
        // 是否有媒体内容
        if(!empty($message_info) && ($message_info['media_type'] > 0)) {
            switch($message_info['media_type']) {
                case 1:
                    $res = $this->message_middle_server->sendImage($message_info['picture'],'users',$tousers,$message_info['greetans'],$ext_data);
                    break;
                case 2:
                    $res = $this->message_middle_server->sendAudio($message_info['voice'],'users',$tousers,$message_info['greetans'],1000,$ext_data);
                    break;
                case 3:
                    $res = $this->message_middle_server->sendAudio($message_info['voice'],'users',$tousers,$message_info['greetans'],1000,$ext_data);
                    break;
            }
        }
        return $res;
    }

    /**
     * 清除系统内置消息
     * @param string|int $gender
     * @param 1   male   => 男生
     * @param -1  female   => 女生
     * @param 0   o   => 人妖
     */
    public function cleanSystemUser($gender = 1)
    {
        switch ($gender) {
            case '1':
            case 'male':
                $this->db_driver->handler->delete($this->system_male_user_table);
                break;
            case '-1':
            case 'female':
                $this->db_driver->handler->delete($this->system_female_user_table);
                break;
            case '0':
            case 'o':
                $this->db_driver->handler->delete($this->system_o_user_table);
                break;
            default:
                $this->db_driver->handler->delete($this->system_male_user_table);
                $this->db_driver->handler->delete($this->system_female_user_table);
                $this->db_driver->handler->delete($this->system_o_user_table);
                break;
        }
    }

    /**
     * 清除系统内置消息
     * @param string|int $gender
     * @param 1   male   => 男生
     * @param -1  female   => 女生
     * @param 0   o   => 人妖
     */
    public function cleanSystemGreet($gender = 1)
    {
        switch ($gender) {
            case '1':
            case 'male':
                $this->db_driver->handler->delete($this->system_greet_for_male_table);
                break;
            case '-1':
            case 'female':
                $this->db_driver->handler->delete($this->system_greet_for_female_table);
                break;
            case '0':
            case 'o':
                $this->db_driver->handler->delete($this->system_greet_for_o);
                break;
            default:
                $this->db_driver->handler->delete($this->system_greet_for_male_table);
                $this->db_driver->handler->delete($this->system_greet_for_female_table);
                $this->db_driver->handler->delete($this->system_greet_for_o_table);
                break;
        }
    }


    /**
     * 写入日志
     * @param $title
     * @param $messgae array|string
     */
    public function writeToLog($title, $messgae) {
        $now_time = date('Y-m-d H:i:s', time());
        $log_template = "--------------------------------".$now_time."--".$title."-start---------------------------------------\r\n";
        if(is_array($messgae)) {
            $log_template .= print_r($messgae, true)."\r\n";
        } else {
            $log_template .= $messgae;
        }
        $log_template .= "---------------------------------end---------------------------------------";
        file_put_contents($this->_logFile,$log_template, FILE_APPEND);
    }
    /**
     * reset protype
     * @param $name
     * @param $value
     */
    public function __set($name,$value){
        $this->$name = $value;
    }

    public function __get($name){
        return $this->$name;
    }
}