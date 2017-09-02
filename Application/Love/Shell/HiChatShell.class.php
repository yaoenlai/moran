<?php
/*
 * 定时脚本,针对新用户打招呼脚本
 * @author yyl
 * @email 944677073@qq.com
 * */
namespace Love\Shell;
use Love\Shell\Shell;
use Common\Common\MessageModule;
class HiChatShell extends Shell {

    public function _initialize()
    {
        parent::_initialize();

    }

    /**
     *
     * 系统自动发送消息
     * /usr/local/php/bin/php shell.php Love/HiChat/Auto 每隔 5分钟运行一次
     *
     */
    public function Auto() {
    	$file = '/tmp/sendmsg.txt';
		if(is_file($file)){//如果存在就退出
			$restartTime = time()-file_get_contents($file);
			echo "上个任务还未运行完！再过".((7200-$restartTime)/60)."分将会重启任务\r\n";
			if($restartTime > 7200)
				unlink($file);
			exit;
		}
		echo date('y-m-d h:i:s')." 运行开始\r\n";
		file_put_contents($file, time());
        $today_time = strtotime(date('Y-m-d', time()));
        $now_time = time();
        $message_module = new MessageModule($this->huanxin);
        // 读取系统 所有招呼信息
        $greetrobot_model = D('Love/Greetrobot');
        $vip_model = D('Love/Vip');
        $userext_model = D('Love/Userext');
        $user_model = D('Love/User');
        //$map['update_time'] = ['gt', $today_time];
        //发送次数不能大于8次
        $map['times'] = ['lt', 8];
        $time = $now_time - 259200;
        //超过三天没有登陆的不推送
        $map['update_time'] = ['gt',$time];
        $count = $greetrobot_model->where($map)->count();
        //每100条一次
        $total_times = ceil($count/100);var_dump($count);
        for($i = 0; $i < $total_times; $i++) {
            $list_chats = $greetrobot_model->where($map)->order('uid desc')->limit($i*100 . ',100')->select();
            //var_dump($list_chats);exit;
            if(!empty($list_chats)) {
                foreach($list_chats as $k=>$v) {

                    $to_user  = $user_model->alias('a')->join('__ADDON_HXUSER__ b ON a.id = b.uid')->field('a.id,a.username,a.nickname,a.gender,a.update_time')->where(['id' => $v['uid']])->find();

                    // 用户最后登录时间大于3天，则主动抛弃用户

                    /* if(($now_time - $to_user['update_time']) > 259200 ) {
                        continue;
                    }*/
					
					if($to_user['gender'] == '-1'){//不给女用户发送
						continue;
					}

                    // 是否为 VIP 用户，VIP 用户不需要发送消息给他了
                    $userExt = $userext_model->find($v['uid']);
					$isVip = $vip_model->isVip($v['uid'],$userExt['sid'],true);
					//echo '|['.$isVip."]-".$userExt['sid']."-".$v['uid'];
					//if($k/10 == 0) echo "\r\n";
                    if($isVip != false) {
                        continue;
                    }

                    // 查询发件人和收件人的个人信息
                    $from_user  = $user_model->alias('a')->join('__ADDON_HXUSER__ b ON a.id = b.uid')->field('a.id,a.username,a.avatar,a.nickname,a.gender,a.update_time')->where(['id' => $v['robot_uid']])->find();
					$from_user['avatar_url'] = str_replace('http://__ROOT__',C('WEB_DEFAULT_DOMAIN'),$from_user['avatar_url']);//修正头像
                    if($this->checkTimer($v['times'], $v['update_time'],$now_time)) {

                        // 获取消息系统消息
                        $message_info = $message_module->getGreetByCondition($to_user['gender'], $v['times']);
						if(!empty($message_info['greeting'])){
							$sendRes = $message_module->sendMessageToHx($from_user, [$to_user['username']], $message_info);
							var_dump($sendRes['data']);
							echo date('y-m-d h:i:s')." 消息发送成功!\r\n";
							if($v['uid'] == $tmpUid)
								sleep(rand(0,15));//如果当前循环里的uid和上次相同，则休眠
							echo date('y-m-d h:i:s')." 当前循环UID:{$v['uid']}|上次循环UID:{$tmpUid}\r\n";
							$tmpUid = $v['uid'];//记录当前循环的uid，供下次判断使用
						}
                        // 更新 表
                        $up_data['times'] = $v['times'] + 1;
                        $up_data['update_time'] = $now_time;

                        $greetrobot_model->where(['uid' => $v['uid'],'robot_uid' => $v['robot_uid']])->data($up_data)->save();
                        //$greetrobot_model->where(['uid' => $v['uid'],'robot_uid' => $v['robot_uid']])->setInc('times',1);
						//更新用户收信数量
						$userext_model->where(['uid' => $v['uid']])->setInc('msg_num',1);
                        // 添加机器人的条件是 10~15
                        $userext_info = $userext_model->where(['uid' => $v['uid']])->find();
                        if(!empty($userext_info) && $userext_info['robot_num'] < 45) {
							$robot_count = $greetrobot_model->where(['uid' => $v['uid']])->count();
							if($robot_count<8 && $v['times']>0){
								if($robot_count<4){
									$robot =true;
								}elseif( $robot_count>3 and $v['times']>1){
									$robot =true;
								}else{
									$robot =false;
								}
								if($robot){
									// 随机一个系统用户
									//$gender = ($v['gender'] == '-1') ? 1 : "-1";
		                            $system_user = $message_module->getSystemUser($v['gender']);
									$robot_uid = $system_user['id'] ? $system_user['id'] : rand(5,4961);
		                            $data['uid'] = $v['uid'];
		                            $data['robot_uid'] = $robot_uid;
		                            $data['times'] = 0;
		                            $data['status'] = 1;
		                            $data['create_time'] = $now_time;
		                            $data['update_time'] = $now_time;
									$robotInfo = $greetrobot_model->where(array('uid'=>$v['uid'],'robot_uid'=>$robot_uid))->find();
		                            if(empty($robotInfo))
		                            	$greetrobot_model->data($data)->add();
		
		                            //更新用户机器人统计表 机器人数量
		                            $user_ext_map['robot_num'] = $userext_info['robot_num'] + 1;
		                            $user_ext_map['msg_num'] = $userext_info['msg_num']+1;
		                            $user_ext_map['update_time'] = $now_time;
		                            $userext_model->where(['uid' => $v['uid']])->data($user_ext_map)->save();//更新时间
									//$userext_model->where(['uid' => $v['uid']])->setInc('robot_num',1);
								}
							}else{
								$user_ext_map['update_time'] = $now_time;
								$user_ext_map['robot_num'] = $robot_count;
								$userext_model->where(['uid' => $v['uid']])->data($user_ext_map)->save();
							}
                        }
                    }
                }
                unset($list_chats);
            }
        }
		@unlink($file);echo date('y-m-d h:i:s')." 运行结束\r\n";
    }

    /**
     * 检查是否发送消息
     * 3 6 9 16 26 40 1小时 2小时
     * @param $times        次数
     * @param $lastTime     最后一次发送消息时间
     * @param $now_time     现在时间
     * @return bool
     */

    public function checkTimer($times, $lastTime, $now_time) {

        if(($times == 0) && (($now_time-$lastTime) > 180)) {
            return true;
        }
        if(($times == 1) && (($now_time-$lastTime) > 360)) {
            return true;
        }
        if(($times == 2) && (($now_time-$lastTime) > 540)) {
            return true;
        }
        if(($times == 3) && (($now_time-$lastTime) > 960)) {
            return true;
        }
        if(($times == 4) && (($now_time-$lastTime) > 1560)) {
            return true;
        }
        if(($times == 5) && (($now_time-$lastTime) > 2400)) {
            return true;
        }
        if(($times == 6) && (($now_time-$lastTime) > 3600)) {
            return true;
        }
        if(($times == 7) && (($now_time-$lastTime) > 7200)) {
            return true;
        }
        return false;
    }
}
