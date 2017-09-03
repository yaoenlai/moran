<?php

// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------

namespace Love\Controller;

use Home\Controller\HomeController;

/**
 * 消息控制器
 * @author zxq
 */
class CommonController extends HomeController {

    //不需要限制权限的模块
    private $noAuthCtl = array('Center', 'Index', 'User', 'Order');

    /**
     * 初始化方法
     * @author zxq
     */
    protected function _initialize() {
        parent::_initialize();
        if (!in_array(CONTROLLER_NAME, $this->noAuthCtl)) {
            $this->is_login();
        }
        //$this->assign('_module_layout', C('MODULE_LAYOUT'));
    }
    
    
    /**
     * 生成API鉴权码
     * @param  array $user 用户信息
     * @return string      鉴权码
     */
    protected function generate_access_token($user, $expiry) {
        $content = $expiry . '|' . $user['phone'] . '|' . $user['id'] . "|" . $user["status"];
        $token = aes128_encode(C('SECRET_KEY'), $content);
        $token = $this->encode_access_token($token);
        return $token;
    }

    /**
     * 刷新API鉴权码
     * @param  string $token 原始鉴权码
     * @return string        新鉴权码
     */
    protected function refresh_access_token($token, $expiry) {
        $token = $this->decode_access_token($token);
        $token = aes128_decode(C('SECRET_KEY'), $token);
        $comps = explode('|', $token);
        $comps[0] = $expiry;

        $newToken = implode('|', $comps);
        $newToken = aes128_encode(C('SECRET_KEY'), $newToken);
        $newToken = $this->encode_access_token($newToken);
        return $newToken;
    }

    /**
     * 从API鉴权码中解析信息
     * @param  string $token 鉴权码
     * @return array         数据数组
     */
    protected function parse_access_token($token) {
        if ($token) {
            $token = $this->decode_access_token($token);
            $token = aes128_decode(C('SECRET_KEY'), $token);
            $data = explode('|', $token);
            return $data;
        }
        return null;
    }
    
    /**
     * 对加密得到的原始鉴权码数据进行编码，并使之变得 http url 友好
     * @param  mixed $token  鉴权码数据
     * @return string        http url友好的字符串鉴权码
     */
    private function encode_access_token($token) {
        $token = base64_encode($token);
        $token = str_replace('+', '-', $token);
        $token = str_replace('/', '_', $token);
        return $token;
    }

    /**
     * 对编码后的鉴权码进行解码
     * @param  string $token  鉴权码
     * @return mixed          原始鉴权码数据
     */
    private function decode_access_token($token) {
        $token = str_replace('-', '+', $token);
        $token = str_replace('_', '/', $token);
        $token = base64_decode($token);
        return $token;
    }
    
}
