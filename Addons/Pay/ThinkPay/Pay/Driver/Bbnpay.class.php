<?php
// +----------------------------------------------------------------------
// | OpenCMF [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.opencmf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: ijry <ijry@qq.com> <http://www.corethink.cn>
// +----------------------------------------------------------------------
namespace Addons\Pay\ThinkPay\Pay\Driver;
/**
 *功能：bbnpay接口公用函数
 *详细：该页面是请求、通知返回两个文件所调用的公用函数核心处理文件
 *版本：1.0
 *修改日期：2017-03-16
'说明：
'以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己的需要，按照技术文档编写,并非一定要使用该代码。
'该代码仅供学习和研究bbnpay接口使用，只是提供一个参考。
 */

class Bbnpay extends \Addons\Pay\ThinkPay\Pay\Pay {

    private $gateway =  'https://payh5.bbnpay.com/cpapi/place_order.php';
    private $paymobilegetway = 'https://payh5.bbnpay.com/browserh5/paymobile.php';
    protected $verify_url = 'http://notify.alipay.com/trade/notify_query.do';

    protected $config = array(
        'appid' => '',
        'appkey' => '',
    );

    public function check1() {
        if (!$this->config['appid'] || !$this->config['appkey']) {
            E("支付宝设置有误！");
        }
        return true;
    }

    /**
     * @param $pay_data
     * {"appid":"123","goodsid":1,"pcorderid":"22222","money":1,"currency":"CHY ","pcuserid":"test","notifyurl":"http://www.iapppay.com/test"}
     */
    public function buildRequestForm($pay_data) {

        $new_data['appid'] = $this->config['appid'];
        $new_data['goodsid'] = $pay_data['goodsid'];
        $new_data['pcorderid'] = $pay_data['out_trade_no'];
        $new_data['money'] =  $pay_data['money']*100;
        $new_data['currency'] = 'CHY';
        $new_data['pcuserid'] = '14';
        $this->config['appkey']= "39486ab16c21172f7e38bd672627f382";
        $request_data = $this->composeReq($new_data, $this->config['appkey']);
        $r = $this->httpPost($this->gateway, $request_data);
        if($r != false) {
            $mobile_data['app'] = $this->config['appid'];
            $mobile_data['transid'] =  $r['transid'];
            $mobile_data['backurl'] = $this->config['notify_url'];
            $mobile_r_data['data'] = urlencode(json_encode($mobile_data));
            $mobile_r_data['sign'] = urlencode($this->getSign($mobile_data, $this->config['appkey']));
            $mobile_r_data['signtype'] = 'MD5';
            $html = $this->_buildForm($mobile_r_data,$this->paymobilegetway,'GET');
            return $html;
        } else {
            echo 'fail';
        }
    }


    /**
     * 解析response报文
     * $content  收到的response报文
     * $vkey     bbnpay分配appkey，用于验签
     * $respJson 返回解析后的json报文
     * return    解析成功TRUE，失败FALSE
     */
    function parseResp($content, $vkey) {
        $result = array();
        $arr = array_map(create_function('$v', 'return explode("=", $v);'), explode('&', $content));

        foreach($arr as $value) {
            $resp[($value[0])] = $value[1];
        }
        //解析transdata
        if(array_key_exists("transdata", $resp)) {
            $respJson = json_decode(urldecode($resp["transdata"]),1);
        } else {
            $result['return_flg'] = FALSE;
            return $result;
        }
        //验证签名，失败应答报文没有sign，跳过验签
        if(array_key_exists("sign", $resp)) {
            //校验签名
            $sign = $this->getSign($respJson, $vkey);
            if($resp['sign'] == $sign){
                $result['return_flg'] = true;
                $result['transdata'] = $respJson;
                return $result;
            }else{
                $result['return_flg'] = FALSE;
                return $result;
            }
        } else if(array_key_exists("errmsg", $respJson)) {
            $result['return_flg'] = true;
            $result['transdata'] = $respJson;
            return $result;
        }
        $result['return_flg'] = FALSE;
        return $result;

    }

    /**
     * curl方式发送post报文
     * $remoteServer 请求地址
     * $postData post报文内容
     * $userAgent用户属性
     * return 返回报文
     */
    function request_by_curl($remoteServer, $postData, $userAgent) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $remoteServer);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
        $res = curl_exec($ch);
        $data = urldecode($res);
        curl_close($ch);

        return $data;
    }


    /**
     * 组装request报文
     * $reqJson 需要组装的json报文
     * $vkey  cp私钥，格式化之前的私钥
     * return 返回组装后的报文
     */
    function composeReq($reqJson, $vkey, $mobile_pay = false) {

        $sign = $this->getSign($reqJson, $vkey);

        $content = json_encode($reqJson);

        //组装请求报文，目前签名方式只支持RSA这一种
        $sign_name = $mobile_pay ? 'data' : 'transdata';
        $reqData = $sign_name."=".urlencode($content)."&sign=".urlencode($sign)."&signtype=MD5";

        return $reqData;
    }

    function getSign($reqJson, $vkey){
        //获取待签名字符串
        $keystr = "";
        ksort($reqJson);//var_dump($reqJson);exit;
        foreach($reqJson as $k=>$row){
            if($k == 'sign' || $k == 'signtype'){
                continue;
            }
            $keystr .= $k."=".$row."&";
        }
        $keystr .= "key=".$vkey; //echo "<br>signstr:".$keystr."<br>";

        //生成签名
        return md5($keystr);

    }

    //发送post请求 ，并得到响应数据  和对数据进行验签
    function httpPost($Url,$reqData){

        $respData = $this->fsockOpen($Url, 0, $reqData);
        $respJson = $this->parseResp($respData, $this->config['appkey']);
        if(!$respJson['return_flg']) {
            return false;
        }else{
            if($respJson['transdata']['code'] == 200){
                return $respJson['transdata'];

            }else{
                return false;
                //echo "错误码：".$respJson['transdata']['code'].",错误信息：".$respJson['transdata']['errmsg'];

            }

        }

    }

    /**
     * 17-05-06 21:45:33.101 {"transdata":"{\"transtype\":0,\"cporderid\":\"170506030012144537514957\",\"transid\":\"0000931494078293532843803339\",\"pcuserid\":\"14\",\"appid\":\"1902017050593398\",\"goodsid\":\"127\",\"feetype\":4,\"money\":10,\"fact_money\":10,\"currency\":\"CHY\",\"result\":1,\"transtime\":\"20170506214532\",\"pc_priv_info\":\"\",\"paytype\":\"1\"}","sign":"8e7fdbc45df4757ca8f1998e402f8dda","signtype":"MD5"}
    //解析transdata
    if(array_key_exists("transdata", $resp)) {
    $respJson = json_decode(urldecode($resp["transdata"]),1);
    } else {
    $result['return_flg'] = FALSE;
    return $result;
    }
    //验证签名，失败应答报文没有sign，跳过验签
    if(array_key_exists("sign", $resp)) {
    //校验签名
    $sign = $this->getSign($respJson, $vkey);
     * @param $notify
     * @return bool
     */
    public function verifyNotify($notify) {
        $this->config['appkey']= "39486ab16c21172f7e38bd672627f382";
        $rsign = $this->getSign(json_decode($notify['transdata'], true), $this->config['appkey']);
        if($notify['sign'] == $rsign){
            $r = json_decode($notify['transdata'], true);
            $info = array();
            //支付状态
            $info['status'] = ($r['result'] == 1) ? true : false;
            $info['money'] = $r['money'];
            $info['out_trade_no'] = $r['cporderid'];
            $this->info = $info;
            return true;
        }else{
            return false;
        }
    }

    /**
     * @param $notify
     */
    protected function setInfo($notify) {
        $r = json_decode($notify['transdata'], true);
        $info = array();
        //支付状态
        $info['status'] = ($r['result'] == 1) ? true : false;
        $info['money'] = $r['money'];
        $info['out_trade_no'] = $notify['cporderid'];
        $this->info = $info;
    }

    /**
     * 支付成功
     */
    public function notifySuccess() {
        echo 'success';
    }
}
