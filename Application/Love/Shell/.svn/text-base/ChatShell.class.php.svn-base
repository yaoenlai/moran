<?php
/*
 * 定时脚本,针对新用户打招呼脚本
 * @author yyl
 * @email 944677073@qq.com
 * */
namespace Love\Shell;
use Love\Shell\Shell;
use Common\Common\MessageModule;
class ChatShell extends Shell {

    public function _initialize()
    {
        parent::_initialize();

    }

    /**
     * 每天只能回复15次
     * 运行方式: php shell.php Love/AutoHi
     */
    public function AutoHi() {
        $message_module = new MessageModule($this->huanxin);
        $message_module->processingMessage();
    }

    /**
     * 测试 加入队列
     * 运行方式: php shell.php Love/AutoJoin
     */
    public function AutoJoin() {

        $user_data = [
            4958 => [
                'uid' => 4958,
                'username' => 'yh98360737',
                'gender' => -1,
                'hx_uid' => '06593010-5955-11e7-bd5a-8984cc481c7c',
            ],

        ];
        $user_index = array_rand($user_data,1);
        $message_module = new MessageModule($this->huanxin);
        $message_module->joinMessageQueue($user_data[$user_index]);
    }
}