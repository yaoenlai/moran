<?php
// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------
namespace Addons\Jpush\Model;
use Think\Model;
use Home\Controller\AddonController;
require_once dirname(dirname(__FILE__)).'/JPush/JPush.php';
/**
 * 推送模型
 * @author zxq
 */
class JpushModel {
    /**
     * 单个用户推送消息发送函数
     * @param string $push_data 推送消息结构
     * @return boolean
     * @author zxq
     */
    function send($push_data) {
        $addon_config = \Common\Controller\Addon::getConfig('Jpush');
        if ($addon_config['status']) {
            // 获取用户对应的设备识别
            $push_token_list = D('User/MessagePush')->where(array('uid' => $push_data['to_uid']))->getField('token', true);
            if ($addon_config['production']) {
                $production = true;
            } else {
                $production = false;
            }
            if ($push_token_list) {
                // 初始化
                $client = new \JPush($addon_config['app_key'], $addon_config['master_secret']);
                $result = null;
                foreach ($push_token_list as $key => $push_token) {
                    if ($push_token) {
                        // 简单推送
                        $tmp = $client->push()
                            ->setPlatform('all')
                            ->addRegistrationId($push_token)
                            ->setNotificationAlert($push_data['title'])
                             ->addAndroidNotification($push_data['title'], $push_data['title'], 1, array("url" => $push_data['url']))
                            ->addIosNotification($push_data['title'], 'default', '+1', true, 'iOS category', array("url" => $push_data['url']))
                            ->setOptions($sendno = null, $time_to_live = null, $override_msg_id = null, $apns_production = $production, $big_push_duration = null)
                            ->send();

                        if ($tmp) {
                            $result[$key] = $tmp;
                        }
                    }
                }
                if ($result) {
                    return $result;
                } else {
                    $this->error = '推送失败';
                    return false;
                }
            } else {
                $this->error = '该用户无可推送设备';
                return false;
            }
        } else {
            $this->error = '插件关闭';
            return false;
        }
    }
}
