<?php

// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------

namespace Love\Api;

use Home\Controller\ApiController;

/**
 * 聚合平台接口父控制器
 * @author zxq
 */
class Api extends ApiController {

    public $sid; //子站的唯一id

    //默认加载的公共信息

    protected function _initialize() {
        parent::_initialize();
        if (empty($this->requestData['sid']))
            $sid = $this->requestData['sid'] = I('request.sid', 0);
            $this->sid = $this->requestData['sid'];
//            $data['sid'] = $sid;
            
        if ($sid>0) {
            $this->ajaxReturn(returnInfo('-1', 'sid不能为空', null, $this->infoType), $this->returnType);
        }
    }

    /**
     * 共用验签数据真伪
     * @author zxq
     * @param string $keyType 签名key(sid,key,secret):默认是key
     * @return boolean true/False
     */
    public function verifySign($keyType = 'key') {
        $post = $this->requestData;
        $sign = $post['sign']; //原始sign
        $iSign = $this->createSign($post, $keyType); //计算的sign
        if ($iSign == $sign) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * 生成签名
     * @author zxq
     * @param array $post 待签名数据
     * @param string $keyType 签名key(sid,key,secret):默认是key
     * @return string/False
     * 正常签名用key 支付通知签名用secret
     */
    public function createSign($post, $keyType = 'key') {
        if (empty($post))
            $post = $this->requestData;
        if (empty($post['sid']))
            return FALSE;
        $devInfo = D('Love/Site')->getSiteConf($post['sid']);
        $key = $devInfo[$keyType];
        //$key ='b1adf3c21a3b7a1573999bf07a10baf5';
        if (empty($key))
            $key = $devInfo['key'];
        $sign = $post['sign'];
        foreach ($this->notSign as $v) {//注销不参与签名的键
            unset($post[$v]);
        }//dump($post);dump(json_encode($post));
        $iSignStr = createLinkstring(argSort($post)) . '&' . $keyType . '=' . $key;
//        echo "签名前：".$iSignStr;
//        echo "<hr/>";
        $iSign = md5($iSignStr);
//        echo "签名后：".$iSign;
//        exit();
        //dump($iSignStr);dump($iSign);//exit;
        file_put_contents('/tmp/sign.txt', microDate("y-m-d H:i:s.x") . ' 接受的数据:' . json_encode($post) . "\r\n待签名串:" . $iSignStr . "\r\n签名结果:" . $iSign . "(" . $sign . ")\r\n", FILE_APPEND);
        return $iSign;
    }

    public function checkDevice($post) {
        if (empty($post))
            $post = $this->requestData;
        $onTime = time(); //当前时间
        $deviceInfo = array();
        $code = 1;
        //if(!empty($post['imeil'])){
        $object = D('Admin/Device'); //实例化设备信息Db
        $map['imeil'] = ($post['imeil']) ? $post['imeil'] : '1234567890';
        //$map['module'] = D('Admin/Module')->getModuleID();
        $deviceInfo = $object->where($map)->find();
        if (empty($post['sdk'])) {
            $aggid = 1;
        } else {
            $aggid = $post['sdk'];
        }
        if (empty($deviceInfo)) {
            if (empty($post['deviceinfo']))
                $post['deviceinfo'] = getOSInfo();
            //添加新增设备
            $deviceinfo = explode('|', $post['deviceinfo']);
            if (strpos($deviceinfo['0'], '+') !== FALSE) {
                $imobile = substr(trim($deviceinfo['0']), 3);
            } else {
                $imobile = trim($deviceinfo['0']);
            }
            $deviceInfo['aggid'] = $aggid;
            $deviceInfo['gid'] = $this->gid;
            $deviceInfo['mobile'] = $imobile;
            $deviceInfo['module'] = D('Admin/Module')->getModuleID(); //$map['module'];
            $deviceInfo['create_time'] = $onTime;
            $deviceInfo['update_time'] = $onTime;
            $deviceInfo['deviceinfo'] = $post['deviceinfo'];
            $deviceInfo['imeil'] = ($post['imeil']) ? $post['imeil'] : '1234567890';
            $re = $object->create($deviceInfo);
            if ($re) {
                $deviceInfo['id'] = $object->add(); //成功返回自增id 例如：21
            } else {
                $code = '-5';
                $msg = $object->getError();
            }
        } else {
            if (empty($deviceInfo['aggid']) || empty($deviceInfo['gid'])) {
                $updateData['id'] = $deviceInfo['id'];
                $updateData['aggid'] = $aggid;
                $updateData['gid'] = $this->gid;
                $updateData['update_time'] = $onTime;
                $saveDevice = $object->save($updateData);
                if (!$saveDevice) {
                    $code = '-8';
                    $msg = '更新设备信息失败!';
                } else {
                    $deviceInfo['aggid'] = $aggid;
                }
            }
        }
        /*    	}else{
          $code = '-9';$msg = 'imeil不能为空!';
          } */
        return returnMsg($code, $msg, $deviceInfo);
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

    /**
     * 生成API鉴权码
     * @param  array $user 用户信息
     * @return string      鉴权码
     */
    protected function generate_access_token($user, $expiry) {
        $content = $expiry . '|' . $user['uid'];
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

}
