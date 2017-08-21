<?php
// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------
namespace Addons\Alidayu\Model;
use Think\Model;
use Home\Controller\AddonController;
/**
 * 邮件控制器
 * @author zxq
 */
class AlidayuModel {
    /**
     * 短信发送函数
     * @param string $sms_data 短信信息结构
     * @$sms_data['RecNum'] 收件人手机号码
     * @$sms_data['code']验证码内容
     * @$sms_data['SmsFreeSignName']短信签名
     * @$sms_data['SmsTemplateCode']短信模版ID
     * @return boolean
     * @author huangda <huang-da@qq.com>
     */
    function send($sms_data) {
        $addon_config = \Common\Controller\Addon::getConfig('Alidayu');
        if($addon_config['status']){
            include "Addons/Alidayu/sdk/TopSdk.php";
            date_default_timezone_set('Asia/Shanghai'); 
            $SmsParam = json_encode(array('code'=>$sms_data['code'], 'product' => C('WEB_SITE_TITLE')));
            $c = new \TopClient;
            $c->method = 'alibaba.aliqin.fc.sms.num.send';
            $c->appkey = $addon_config['appkey'];
            $c->secretKey = $addon_config['secret'];
            $c->format = "json";
            $req = new \AlibabaAliqinFcSmsNumSendRequest;
            $req->setSmsType("normal");
            $req->setSmsFreeSignName($sms_data['SmsFreeSignName'] ? : $addon_config['sign_name']);
            $req->setSmsParam($SmsParam);
            $req->setRecNum($sms_data['RecNum']);
            $req->setSmsTemplateCode($sms_data['SmsTemplateCode'] ? : $addon_config['template_code']);
            $resp = $c->execute($req);
            $return=json_encode($resp);
            if ($return['result']['err_code']==0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
